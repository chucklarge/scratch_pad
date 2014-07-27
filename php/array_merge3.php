<?php

$a1 = array(
    'a' => 'A',
    'b' => 'B',
    'c' => 'C'
);

$a2 = array(
    'd' => 'D',
    'e' => 'E',
    'f' => 'F'
);

$a3 = array_merge($a1, $a2);

var_dump($a3);
