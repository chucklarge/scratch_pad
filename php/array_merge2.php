<?php

$a1 = array(
    1,2,4,5,6,7,9
);

$a2 = array(
    1,3,7,9,20,32
);
$a3 = array_merge($a1, $a2);


print_r($a1, $a2);
print_r($a2);
print_r(array_unique($a3));
