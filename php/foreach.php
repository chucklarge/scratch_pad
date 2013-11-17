<?php

$letters = array(1 => 'a', 2 => 'b', 3 => 'c');

foreach ($letters as $i => $letter) {
    echo $letter . "\n";
}

var_dump($letters);


foreach ($letters as $i => $letter) {
    echo $letter . "\n";
    unset($letters[$i]);
}

var_dump($letters);
