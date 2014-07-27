<?php

function printConfig($config, $path=[]) {
var_dump($config);
    if (is_array($config)) {
        foreach ($config as $k => $value) {
echo "$k\n";
            //$path[] = $k;
            printConfig($value, $path);
        }
    } else if (is_scalar($config)) {
        //$path[] = $config;
        echo implode('.', $path) ."\n";
    } else {
        echo "NULLLLLLLL\n";
    }

}

$a = [];
foreach(range('a', 'b') as $l0) {
    foreach(range(0, 1) as $n0) {
        foreach(range('a', 'b') as $l1) {
            foreach(range(0, 1) as $n1) {
                $a[$l0][$n0][$l1][$n1] = rand();
            }
        }
    }
}

foreach(range('a', 'b') as $l0) {
    foreach(range(0, 1) as $n0) {
        $a[$l0][$n0] = rand();
    }
}
/*
$a = [
    'a' => [
        1 => [
            'a1' => 99999
        ],
    ],
];
*/

printConfig($a);
