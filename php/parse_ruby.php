<?php
$f = file_get_contents('snap_columns.rb');

$f = preg_replace("/#(.*?)\n/", "\n",$f);
$f = preg_replace('/\s+/', ' ',$f);
$regex = '/SNAP_COLUMNS = {(.*?)}\.freeze/';
$matches = [];
if (preg_match_all($regex, $f, $matches)) {
  $tables = $matches[0][0];
}

$t = preg_replace("/,/", ",\n", $tables);
$t = preg_replace("/\[/", "[\n", $t);
$t = preg_replace("/^:/", "", $t);
$t = preg_replace("/'/", "\"", $t);
$t = preg_replace("/SNAP_COLUMNS = /", "", $t);
$t = preg_replace("/\.freeze/", "", $t);
echo $t . "\n";

//$a = json_decode($t, true);
//var_dump($a);


