<?php

class Tree {
    public $value = null;
    public $left  = null;  // left pointer
    public $right  = null;  // right pointer

    public function __construct($value) {
        $this->value = $value;
    }

    public function setRight(Tree $t) {
        $this->right = $t;
    }

    public function setLeft(Tree $t) {
        $this->left = $t;
    }
}

function inOrderTraversal(Tree $t) {
    $current = $t;
    $pre = null;

    while ($current != null) {
        if ($current->left == null) {
            print $current->value ." ";
            $current = $current->right;
        }
        else {
            $pre = $current->left;
            while($pre->right != null && $pre->rptr != $current) {
                $pre = $pre->right;
            }

            if ($pre->right == null) {
                $pre->right =  $current;
                $current = $current->left;
            }
            else {
                $pre->right = null;
                print $current->value . " ";
                $current = $current->right;
            }
        }
    }
}

function BreadthFirstLevelPrint(Tree $tree) {
    $q = array();
    $q[] = $tree;
    $print = array();
    $level = 0;
    $currentCount = 1;
    $c = 0;
    while (!empty($q)) {
        $t = array_shift($q);
        $currentCount--;
        print $t->value;

        $o = $t->left;
        if ($o != null) {
            $q[] = $o;
            $c++;
        }

        $o = $t->right;
        if ($o != null) {
            $q[] = $o;
            $c++;
        }

        if ($currentCount == 0) {
            print "\n";
            $currentCount = $c;
            $c = 0;
        }

    }
}

/*
      A
    /   \
   B     C
 /      /  \
D     E     F

*/

$a = new Tree('A');
$b = new Tree('B');
$c = new Tree('C');
$d = new Tree('D');
$e = new Tree('E');
$f = new Tree('F');

$b->setLeft($d);

$c->setLeft($e);
$c->setRight($f);

$a->setLeft($b);
$a->setRight($c);

//inOrderTraversal($a);
BreadthFirstLevelPrint($a);
