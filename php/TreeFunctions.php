<?php
class TreeFunctions {
    static public function inOrderTraversal(BinaryTree $t) {
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

    static public function BreadthFirstLevel(BinaryTree $tree) {
        $q = array();
        $q[] = $tree;
        $print = array();
        $level = 0;
        $currentCount = 1;
        $c = 0;
        while (!empty($q)) {
            $t = array_shift($q);
            $currentCount--;
            print $t->value . " ";

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


    static public function depthFirstPreOrder(BinaryTree $tree) {
        if ($tree->left) {
            self::DepthFirstPreOrder($tree->left);
        }
        print $tree->value . " ";
        if ($tree->right) {
            self::DepthFirstPreOrder($tree->right);
        }
    }

    static public function depthFirstInOrder(BinaryTree $tree) {
        print $tree->value . " ";
        if ($tree->left) {
            self::DepthFirstInOrder($tree->left);
        }
        if ($tree->right) {
            self::DepthFirstInOrder($tree->right);
        }
    }

    static public function depthFirstPostOrder(BinaryTree $tree) {
        if ($tree->left) {
            self::DepthFirstPostOrder($tree->left);
        }
        if ($tree->right) {
            self::DepthFirstPostOrder($tree->right);
        }
        print $tree->value . " ";
    }

}
