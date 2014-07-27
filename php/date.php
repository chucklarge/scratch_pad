<?php
date_default_timezone_set('America/New_York');
$ts = 1405561858;
echo date('Y-m-d', $ts) . "\n";

echo strtotime(date('Y-m-d', $ts)) . "\n";

