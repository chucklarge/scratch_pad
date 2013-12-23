<?php

$json = '{
    "pages_index": [
        "page_id:long"
    ]
 }';

$json = preg_replace("/\n/", "", $json);
$a = json_decode($json, true);

var_dump($a);
