<?php

$files = [
    ".",
    "..",
    "etsy_aux.login_session_detail.tsv.gz",
    "etsy_aux.login_session_detail.tsv2108.gz",
    "etsy_aux.login_session_detail.tsv2109.gz",
    "etsy_aux.login_session_detail.tsv2110.gz",
    "etsy_aux.login_session_detail.tsv2111.gz",
    "etsy_aux.login_session_detail.tsv2112.gz",
    "etsy_aux.login_session_detail.tsv2113.gz",
    "lock",
];


foreach ($files as $file) {
    $matches = [];
    if(preg_match("/.+\.gz/", $file)) {
        print $file."\n";
    }
}
