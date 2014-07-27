<?php

$default = [
    'can_edit' => false,
    'can_admin' => false,
    'can_view' => true,
    'can_run' => true,
];

$actual = [
    'can_admin' => true,
];

$actual = [];

$a = array_merge($default, $actual);
var_dump($a);
