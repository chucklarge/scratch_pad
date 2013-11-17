<?php

function balance($string, $index = 0) {
    if (isset($string[$index]) == false) {
        return "";
    }
    if ($string[$index] == ")") {
        return ")";
    }
    if ($string[$index] == "(") {
        $closer = balance($string, $index + 1);
        if ($closer == ")") {
            return balance($closer, $index + 1);
        }
        return $index - 1;
    }
    return balance($string, $index + 1);
}

$a = "()";
$a = "()(";
echo balance($a);


echo "\n";
