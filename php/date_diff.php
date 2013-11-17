<?php

$start = new DateTime('2009-10-11');
$end   = new DateTime('2009-10-15');
$interval = $start->diff($end);
echo $interval->format('%a');
