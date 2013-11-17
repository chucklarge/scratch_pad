<?php
require_once 'BinaryTree.php';
require_once 'TreeFunctions.php';
/*
      A
    /   \
   B     C
 /      /  \
D     E     F

*/

$a = new BinaryTree('A');
$b = new BinaryTree('B');
$c = new BinaryTree('C');
$d = new BinaryTree('D');
$e = new BinaryTree('E');
$f = new BinaryTree('F');

$b->left = $d;

$c->left  = $e;
$c->right = $f;

$a->left  = $b;
$a->right = $c;

echo TreeFunctions::breadthFirstLevel($a) . "\n";
