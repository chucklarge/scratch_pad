<?php
class V {
    public function __call($method, $args) {
        $this->{$method}($args);
    }

    private function priv() {
        echo "\npriv\n";
        debug_print_backtrace();
    }

    public function pub() {
        echo "\npub\n";
        debug_print_backtrace();
    }
}


$v = new V();

$v->pub();
$v->priv();
