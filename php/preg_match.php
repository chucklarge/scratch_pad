<?php
//$subject = "aas2234";
//$pattern = '/^[a-z0-9]+/u';

$subject = "aas2234.23lasdfb.00";
$pattern = '/^[a-z0-9]+.[a-z0-9]+.00/';
$matches = array();
$success = preg_match($pattern, $subject, $matches);
var_dump($success, $matches);

$subject = "thedogisderp";
$pattern = '/\s/';
$matches = array();
$success = preg_match($pattern, $subject, $matches);
var_dump($success, $matches);

$subject = "the dog is derp";
$pattern = '/\s/';
$matches = array();
$success = preg_match($pattern, $subject, $matches);
var_dump($success, $matches);
