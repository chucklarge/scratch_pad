<?php

/**
 * in-progress admin/rampup testing, then replaces v1 SiteRpc in listing search ctrl. (8/29/11)
 *
 * Use multi-curl to make non-blocking HTTP requests with given timeout.
 * This shan't be abused.
 *
 * Request host can be different than header host to allow
 * hitting 127.0.0.1 with a host name, like www.etsy.com.
 *
 * GET and POST supported.
 *
 * v1 Added for search ads Summer 2011.
 * v2 fixes timeout for case where now is after timeout instant.
 * v2 also more unit testable, curl functions wrapped for mockability.
 */
class SiteRpc2 {

    protected $name; // for logs
    protected $max_wait_ms;
    // manual tests show io loop .02 seconds can read ~ 200k char responses
    protected $slow_caller_extra_max_wait_ms = 20;

    protected $url_host;
    protected $header_host;

    protected $requests;
    protected $options;
    protected $mc_handle;
    protected $mc_running;

    protected $send_time; // microtime float
    protected $timeout_time; // microtime float

    /**
     * @param $max_wait_ms milliseconds receiveResponse will wait before
     * aborting, measured from time sendRequests called. Zero means timeout
     * right away, not wait-forever.
     *
     * @param $url_host IP or FQDN (without http://) used to construct request
     * URL, default 127.0.0.1
     *
     * @param $header_host FQDN (without http://) passed in request header
     * 'Host:' to hit intended vhost when using IP $urlHost
     * NOTE: works on princess.etsy.com passing header Host: www.etsy.com
     */
    public function __construct($name, $max_wait_ms = 1000, $url_host = '127.0.0.1', $header_host = 'www.etsy.com') {
        if (!$name) {
            throw new InvalidArgumentException('SiteRpc requires name (for logs, statsd keys)');
        }
        $this->name = $name;
        $this->max_wait_ms = $max_wait_ms;
        $this->url_host = $url_host;
        $this->header_host = $header_host;
        $this->requests = array();
        $this->options = array();
    }

    /**
     * Add HTTP request and params for multi-curl to execute later async.
     * @param $path must have leading /
     */
    public function addRequest($request_key, $path, $params = null, $post = false) {
        if (!$request_key || !$path) {
            throw new InvalidArgumentException('SiteRpc requires request key, /some/path');
        }

        $t = microtime(true);
        $c = curl_init();

        // Build curl opts arr for unit tests; PHP 'curl' resource not introspectable
        $curl_opts = array();

        if ($this->header_host) {
            $curl_headers = array('Host: ' . $this->header_host);
            $curl_opts[CURLOPT_HTTPHEADER] = $curl_headers; // must be arr, 1 header "key: val" per elm
        }

        $url = 'http://' . $this->url_host . $path;
        if ($post) {
            $curl_opts[CURLOPT_POST] = 1;
            $curl_opts[CURLOPT_POSTFIELDS] = $this->_postFields($params);
        } else { // GET
            if ($params) {
                $url .= '?' . http_build_query($params);
            }
        }
        $curl_opts[CURLOPT_URL] = $url;

        $curl_opts[CURLOPT_HEADER] = 0;
        $curl_opts[CURLOPT_RETURNTRANSFER] = 1;

        $opts_ok = curl_setopt_array($c, $curl_opts);
        if (!$opts_ok) {
            Logger::log_error("SiteRpc failed to set curl opts, not adding request: name={$this->name} key=$request_key
                               path=$path", 'site_rpc');
            return $this;
        }

        $this->requests[$request_key] = $c;
        $this->options[$request_key] = $curl_opts; // for unit tests
        $this->_statsTiming('add_request', $t);

        Logger::log_debug("SiteRpc added request: name={$this->name} key=$request_key path=$path", 'site_rpc');
        return $this;
    }

    /**
     * Return string encoded for curl POSTFIELDS from given array of key value
     * pairs.
     */
    function _postFields($params) {
        if (!$params) {
            return '';
        }
        foreach ($params as $key => $val) {
            $pairs[] = $key . '=' . urlencode($val);
        }
        return implode('&', $pairs);
    }

    /**
     * Send all added HTTP requests without blocking.
     * Return this.
     */
    public function sendRequests($now = null) {
        if (!$this->requests) {
            throw new InvalidOperationException('SiteRpc cannot send requests, none added');
        }

        $t = microtime(true);
        $this->_curl_multi_init();
        foreach ($this->requests as $rkey => $ch) {
            $this->_curl_multi_add_handle($ch);
        }
        $log_reqs = implode(',', array_keys($this->requests));

        $t1 = microtime(true);
        $cm_exec = $this->_curlMultiExecWhilePerforming();
        // $this->_statsTiming('send_multi_exec', $t1); // really need this fine grained?
        if ($cm_exec != CURLM_OK) {
            throw new Exception("SiteRpc error sending requests: name={$this->name} cm_exec=$cm_exec error=" .
                                curl_error($this->mc_handle));
        }

        $this->_markSent();
        $this->_statsTiming('send_requests', $t);
        Logger::log_debug("SiteRpc sent requests: name={$this->name} send={$this->send_time} reqs=$log_reqs",
                          'site_rpc');
        return $this;
    }

    /**
     * Attempt to retrieve HTTP responses.  If now after timeout instant (send
     * time + max wait), make 1 attempt to read responses curl has processed if
     * any, otherwise is there is still time before timeout instant, wait for
     * responses up to timeout instant.
     *
     * Returns 2-elm array: 0=successful 1=failed responses,
     * each being array of response strings, keyed by original request key.
     * Or returns null if timeout or error.
     *
     * TODO LOW could mark recv attempt and throw exeption if this called twice
     */
    public function receiveResponses() {
        if (!$this->send_time) {
            Logger::log_error('Cannot get HTTP responses, invalid send time; requests never sent?', 'site_rpc');
            return null;
        }

        $profiler = new IOProfiler('site_rpc');

        // Process IO for reading responses, calc timeout instant 2 diff ways:
        // 1) if now already after timeout instant (based on send time), use timeout instant just a few milsec after
        //    now (enough time to curl loop enough to read suitably large response)
        // 2) if now before timeout, loop/wait/block up to timeout instant
        $timeout_time = $this->_calcTimeoutTime();
        $is_response_all_read = $this->_processIoLoopUntilTimeout($timeout_time);
        if (!$is_response_all_read) {
            $this->_closeAll();
            return null; // means timeout or curl/conn error (bad host/url for instance)
        }

        // 2 elm array, 0=ok, 1=fail, each has { req_key:str response, .. }
        $responses = $this->_getContentAndClose();

        $profiler->write("Requests=" . json_encode(array_keys($this->requests)));
        return $responses;
    }

    function _markSent($now = null) {
        $this->send_time = $this->_now();
        Logger::log_debug(sprintf('SiteRpc mark sent: name=%s sent=%f', $this->name, $this->send_time), 'site_rpc');
    }

    function _calcTimeoutTime() {
        $now = $this->_now(); // microtime(true)
        $fast_caller_timeout_time = $this->send_time + ($this->max_wait_ms / 1000);
        $now_after_fast_to = ($fast_caller_timeout_time <= $now);
        if ($now_after_fast_to) {
            $slow_caller_timeout_time = $now + ($this->slow_caller_extra_max_wait_ms / 1000);
            $timeout_time = $slow_caller_timeout_time;
        } else {
            $timeout_time = $fast_caller_timeout_time;
            $slow_caller_timeout_time = 0; // for log
        }
        Logger::log_debug(
            sprintf('SiteRpc calc timeout: to_time=%f -- now=%f fast_to=%f now_after_fast_to=%d slow_to=%f',
            $timeout_time, $now, $fast_caller_timeout_time, $now_after_fast_to, $slow_caller_timeout_time),
            'search_ads');
        return $timeout_time;
    }

    // for unit test overriding
    function _now() {
        return microtime(true);
    }

    /**
     * Curl multi read response loop. Will wait/block only up until given
     * timeout instant. Internally loops wile curl multi running flag is true:
     * 1) curl multi select until some data to process 2) curl multi exec inner
     * loop to process it.  Note: Not all HTTP responses can be processed with
     * 1 exec loop, so use a suitable timeout instant.
     */
    function _processIoLoopUntilTimeout($timeout_time) {
        do {
            // (re)calc duration seconds until timeout instant
            $sec_until_to = $timeout_time - $this->_now();
            if ($sec_until_to <= 0) {
                Logger::log_info(
                    sprintf('SiteRpc proc IO loop TIMEOUT - now past timeout: name=%s to_time=%f sec2to=%f',
                    $this->name, $timeout_time, $sec_until_to,  $this->_isCurlMultiRunning()), 'site_rpc');
                return false;
            }

            // Wait/block for IO activity up to given secs
            // select returns when 1) some or all data avail to exec/process 2) timeout reached 3) multi err (-1)
            $cm_sel = $this->_curl_multi_select($sec_until_to);
            $cm_running = $this->_isCurlMultiRunning();

            if ($cm_sel == -1) {
                Logger::log_info("SiteRpc multi curl select error: name={$this->name}", 'site_rpc');
                $this->_getMultiInfo(); // ??? add curl multi info debug to log? (bad URL err etc)
                return false;
            } elseif ($cm_sel == 0 && $cm_running) {
                // timeout = select returned & multi still running but no handles have data/selected
                $elapsed = $this->_now() - $this->send_time;

                Logger::log_info(
                    sprintf('SiteRpc proc IO loop TIMEOUT - from select: name=%s sec2to=%f sel=%d run=%d elapsed=%f',
                    $this->name, $sec_until_to, $cm_sel, $cm_running, $elapsed), 'site_rpc');
                return false;
            }

            // Tell curl to process its pending/selected data, may or may not finish running whole multi batch
            // curl exec returns right away if no data to proc, so must check running flag
            $cm_exec = $this->_curlMultiExecWhilePerforming();

            // Check curl's flag again since lib can update anytime (???)
            $cm_running2 = $this->_isCurlMultiRunning();
            Logger::log_debug(
                sprintf('SiteRpc proc IO loop: sec2to=%f sel=%d exec=%d run=%d', $sec_until_to,
                        $cm_sel, $cm_exec, $cm_running2), 'site_rpc');

        } while ($cm_running2);
        // IO ready/avail to read: multicurl done running and method didn't short-circuit
        return true;
    }

    function _getContentAndClose() {
        $idx_ok = 0; // numeric idx for list() compat with v1 siterpc
        $idx_fail = 1;
        $responses = array($idx_ok => array(), $idx_fail => array());
        $log_resps = '';
        foreach ($this->requests as $rkey => $ch) {
            // ??? could skip grabbing resp for non 200 but caller might want to inspect it
            $resp = $this->_curl_multi_getcontent($ch);
            $code = $this->_curl_getinfo($ch, CURLINFO_HTTP_CODE);
            // TODO check meanings: resp null vs '' vs false; code zero vs null
            $resp_type = ($code == 200 && $resp !== null) ? $idx_ok : $idx_fail;
            $responses[$resp_type][$rkey] = $resp;
            $log_resps .= " code_$rkey=$code resp_len_$rkey=" . strlen($resp);
        }
        $this->_closeAll();
        // TODO LOW log recv time, send + recv time
        Logger::log_debug("SiteRpc received response(s):$log_resps", 'site_rpc');
        return $responses;
    }

    /**
     * For each request remove and close its curl handle, finally close parent
     * multi-curl handle.
     */
    function _closeAll() {
        $log_closed = array();
        foreach ($this->requests as $rkey => $ch) {
            $rm_code = curl_multi_remove_handle($this->mc_handle, $ch);
            curl_close($ch);
            $log_closed[$rkey] = $rm_code;
        }
        curl_multi_close($this->mc_handle);
        Logger::log_debug('SiteRpc removed and closed handles: close_codes=' . json_encode($log_closed), 'site_rpc');
    }

    function _curlMultiExecWhilePerforming() {
        do {
            $cme = curl_multi_exec($this->mc_handle, $this->mc_running);
        } while ($cme == CURLM_CALL_MULTI_PERFORM);
        // PERFORM = -1 = curl wants exec run again right now
        return $cme;
    }

    function _isCurlMultiRunning() {
        return $this->mc_running;
    }

    function _getMultiInfo() {
        while ($minfo = $this->_curl_multi_info_read()) {
            $err = curl_error($minfo['handle']);
            $chinfo = print_r($this->_curl_getinfo($minfo['handle']), true);
            Logger::log_info("SiteRpc multi_result=" . $minfo['result'] . " ch_err=$err  -- ch_info=$chinfo",
                             'site_rpc');
        }
    }

    /**
     * Return array of CURL options for each successfully added requests.
     * For unit tests, because can't read options from php curl resource.
     */
    function getOptions() {
        return $this->options;
    }

    function _statsTiming($key_suffix, $start_time) {
        if (!$key_suffix || !$start_time) {
            throw new InvalidArgumentException('SiteRpc stats requires key suffix, start time');
        }
        $statsd_key = sprintf('site_rpc.%s.%s', $this->name, $key_suffix);
        $delta = 1000 * (microtime(true) - $start_time);
        Logger::log_debug(sprintf('SiteRpc stats timing: key=%s val=%f', $statsd_key, $delta), 'site_rpc');
        StatsD::timing($statsd_key, $delta, 0.1);
        return $delta;
    }

    // --- wrap non OO curl functs for unit tests ---

    function _curl_multi_init() {
        $this->mc_handle = curl_multi_init();
        return $this->mc_handle;
    }

    function _curl_multi_add_handle($curl_handle) {
        return curl_multi_add_handle($this->mc_handle, $curl_handle);
    }

    function _curl_multi_select($timeout_sec) {
        // TODO throw exception for zero timeout, not just log
        if ($timeout_sec == 0) {
            Logger::log_error('SiteRpc multi curl select with unexpected zero, means block "forever"', 'site_rpc');
        }
        $cm_sel = curl_multi_select($this->mc_handle, $timeout_sec);
        return $cm_sel;
    }

    function _curl_multi_getcontent($ch) {
        return curl_multi_getcontent($ch);
    }

    function _curl_multi_info_read() {
        return curl_multi_info_read($this->mc_handle);
    }

    function _curl_getinfo($ch, $opt = 0) {
        $i = curl_getinfo($ch, $opt);
        //Logger::log_debug("SiteRpc info=" . print_r($i, true), 'site_rpc');
        return $i;
    }
}
