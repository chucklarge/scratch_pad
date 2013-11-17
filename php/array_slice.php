<?php
$input = array("a", "b", "c", "d", "e");
$str = 'abc';
var_dump($input);
$input = array_slice($input, strlen($str));
var_dump($input);
