<?php

class huhhh {
    public $ccc = 'ccc';
    public $ddd = 'ddd';
}

class derp {
    public $aaa = 'aaa';
    public $fff;

    public function __construct() {
        $this->fff = new huhhh();
    }

    public function bbb() {
        return new huhhh();
    }
}

$d = new derp();

echo $d->aaa . "\n";
echo $d->bbb()->ccc . "\n";
echo $d->fff->ddd . "\n\n";

$actions = array(
    'aaa',
    'bbb()->ccc',
    'fff->ddd',
);

foreach ($actions as $action) {
    $e = eval("return \$d->$action;");
    echo $e . "\n";
}


