<?php

$a = array(
    ':DATE_START' => '2012_05_10',
    ':DATE_END'   => '2012_07_12',
);
$j = json_encode($a);
//$a = array(
    //':LIMIT' => 100,
//);

$a = array(
    ':LIMIT',
    ':DATE_START',
    ':DATE_END'
);
$j = json_encode($a);
echo "${j}\n";

$a = json_decode($j, true);
print_r($a);



$j = json_encode('');
echo "${j}\n";
