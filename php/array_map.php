<?php

$a = [1, 2, 3, 4, 5];

$b = array_map(function($e) { return ['num' => $e]; }, $a);

var_dump($b);
