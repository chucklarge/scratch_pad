<?php

$header_cookie = "asd;;sd=;dev-user_prefs=1&2596706699&q0tPzMlJLaoEAA==; __qca=P0-1679370611-1344909560899; LD=0; compat_test=7A24BC67; uaid=uaid%3DgDxRU8nMJHtQHJhqmBcrZAphjpKr%26isaa%3D%26ab_uid%3D7454968%26ab_aflag%3D1%26_now%3D1345486946%26_slt%3DxkVTFjm9%26_kid%3D1%26_ver%3D1%26_mac%3Du6RbyKMSsMAUoiiOFeOZ-LBUVK-NeU99uD2p4lKkpoM.; user_prefs=1&2031531379&q0tPzMlJLaqsAwJDAA==; grvinsights=2b5cdd83c036f4d4a43e73cb7b722ca3; kvcd=1346796463236; kt-v1-0-1-_etsy_com=1%3A3966943fdb4a4616136b678d71da200dc4cae2db%3A1346848792%3Aaf2f3328e3494eb13cd6cc7ee17abb7a221cf54cf7f28268aed845cceedf1510b9aad0d0c797c075; LD=0; compat_test=7A24BC67; uaid=uaid%3DgDxRU8nMJHtQHJhqmBcrZAphjpKr%26isaa%3D%26ab_uid%3D7454968%26ab_aflag%3D1%26_now%3D1347041110%26_slt%3DkC3EbuwN%26_kid%3D1%26_ver%3D1%26_mac%3D_HDHLAs_7_RqeaVwnyf5XDcU6_D7x1I4ueTwMMa95Q0.; autosuggest_split=1; last_browse_page=http%3A%2F%2Fwww.etsy.com%2Fshop%2Fmodulem; etala=111461200.168076149.1343994989.1347466139.1347479564.96.0; etalb=111461200.6.10.1347479631; search_min_price=COOKIE_VAL_ZERO; search_max_price=COOKIE_VAL_ZERO; search_sort_by=most_relevant; ship_to=ZZ; __utma=111461200.677971537.1343994991.1347403170.1347479565.111; __utmb=111461200.6.10.1347479565; __utmc=111461200; __utmz=111461200.1346961347.100.18.utmcsr=google|utmccn=(organic)|utmcmd=organic|utmctr=http://www.etsy.com/listing/92669650/two-2-custom-latitude-and-longitude?ref%3Dsr_gallery_7%26sref%3D%26%20; __utmv=111461200.error_-1; last_browse_page=%2F; __utma=47245436.443501427.1343994134.1346964740.1347480644.28; __utmb=47245436.3.10.1347480644; __utmc=47245436; __utmz=47245436.1343994134.1.1.utmcsr=(direct)|utmccn=(direct)|utmcmd=(none); __utmv=47245436.error_-1; RT=s=1347480711815&r=http%3A%2F%2Fwww.jdavis.vm.ny4dev.etsy.com%2F; etala=111461200.168076149.1343994989.1347466139.1347479564.96.0; etalb=111461200.7.10.1347480772";

$wanted_cookies  = array('__utma', 'etala', 'etalb');

$cookies = preg_split("/;/", $header_cookie);

$results = array();
foreach ($cookies as $cookie) {
    $matches = array();
    preg_match("/^([^\=]+)=(.*)$/", trim($cookie), $matches);
    if (isset($matches[0]) && isset($matches[1]) && in_array($matches[1], $wanted_cookies)) {
        $results[] = $matches[0];
    }
}
$actual = implode(';', $results);
$expected = 'etala=111461200.168076149.1343994989.1347466139.1347479564.96.0;etalb=111461200.6.10.1347479631;__utma=111461200.677971537.1343994991.1347403170.1347479565.111;__utma=47245436.443501427.1343994134.1346964740.1347480644.28;etala=111461200.168076149.1343994989.1347466139.1347479564.96.0;etalb=111461200.7.10.1347480772';


echo $actual === $expected ? 'true' : 'false';
echo "\n";

