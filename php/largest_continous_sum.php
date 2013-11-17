<?php
function largestContinousSum($a) {
    if (empty($a)) {
        return;
    }

    $maxSum     = $a[0];
    $currentSum = $a[0];

    for ($i=1; $i< count($a); $i++) {
        $currentSum = max($currentSum + $a[$i], $a[$i]);
        $maxSum     = max($currentSum, $maxSum);
    }

    return $maxSum;
}

$a = array(9, 3, 4, -100, 3, 4, 8, 2);

echo largestContinousSum($a) . "\n";
