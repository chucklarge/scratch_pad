<?php

$array1 = array('blue'  => 1, 'red'  => 2, 'green'  => 3, 'purple' => 4);

function blacklist($key) {
echo $key . "\n";
    $filter = array('green', 'blue', 'cyan');
    return !in_array(strtolower($key), $filter);
}


var_dump(array_filter($array1, 'blacklist'));
