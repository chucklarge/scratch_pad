<?php
$subject = "select (:derp), `:AAA` from ::whoa
:derp :duhhh where state = \":depnd123\" and :we23sf > 4";

//$pattern = '/:[\w_]+/';
$pattern = '/["|(|\s|`](:[\w_]+)/';

$matches = array();
$success = preg_match_all($pattern, $subject, $matches);

$m = $matches[1];
sort($m);
var_dump(array_unique($m));


/*
echo $subject . "\n";
$subject = preg_replace($pattern, '?', $subject);
echo $subject . "\n";
*/
