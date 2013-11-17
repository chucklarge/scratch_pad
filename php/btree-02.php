<?php
require_once 'BinaryTree.php';
require_once 'TreeFunctions.php';

/*
                  A
                /   \
               B     C
             /      /  \
            D     E     F
          /  \      \     \
        G      H      I     J
          \         /     /   \
            K     L     M       N

*/

$a = new BinaryTree('A');

$b = new BinaryTree('B');
$c = new BinaryTree('C');

$d = new BinaryTree('D');
$e = new BinaryTree('E');
$f = new BinaryTree('F');

$g = new BinaryTree('G');
$h = new BinaryTree('H');
$i = new BinaryTree('I');
$j = new BinaryTree('J');

$k = new BinaryTree('K');
$l = new BinaryTree('L');
$m = new BinaryTree('M');
$n = new BinaryTree('N');

$g->right = $k;
$i->left  = $l;
$j->left  = $m;
$j->right = $n;

$d->left  = $g;
$d->right = $h;
$e->right = $i;
$f->right = $j;

$b->left = $d;
$c->left  = $e;
$c->right = $f;

$a->left  = $b;
$a->right = $c;

echo TreeFunctions::breadthFirstLevel($a) . "\n";
echo TreeFunctions::depthFirstPreOrder($a) . "\n";
echo TreeFunctions::depthFirstInOrder($a) . "\n";
echo TreeFunctions::depthFirstPostOrder($a) . "\n";
