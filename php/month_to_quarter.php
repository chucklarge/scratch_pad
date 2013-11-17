<?php
/*
    Q1 - January, February, and March
    Q2 - April, May, and June
    Q3 - July, August, and September
    Q4 - October, November, December
*/
function monthToQuarter($month) {
    return floor(($month - 1) / 3) + 1;
}

foreach (range(1, 12) as $month) {
    echo $month . " " . monthToQuarter($month) . "\n";
}
