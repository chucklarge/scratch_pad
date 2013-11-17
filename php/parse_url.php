<?php
$url = 'http://php.net/manual/en/function.parse-url.php?derp=true&doink=1223&doh=wer&chuck';
$parsed = parse_url($url);
$query = array();
parse_str($parsed['query'], $query);

var_dump($parsed, $query);
