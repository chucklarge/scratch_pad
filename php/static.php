<?php

class Derp1 {
    protected static $guid;
    protected static $count = 0;

    public function __construct() {
        if (!isset(self::$guid)) {
            self::$guid = 'asdfasfasdfasdf';
        }

        self::$count++;
    }

    public function printMe() {
        echo self::$count . " " . self::$guid . "\n";
    }
}

class Derp2 {
    protected static $guid;
    protected static $count = 0;

    public function __construct() {
    }

    public function setMe() {
        if (!isset(self::$guid)) {
            self::$guid = 'asdfasfasdfasdf';
        }

        self::$count++;
    }

    public function printMe() {
        echo self::$count . " " . self::$guid . "\n";
    }
}

$a = new Derp1();
$b = new Derp1();
$c = new Derp1();

$a->printMe();
$b->printMe();
$c->printMe();

echo "\n\n";

$a = new Derp2();
$a->setMe();
$b = new Derp2();
$b->setMe();
$c = new Derp2();
$c->setMe();

$a->printMe();
$b->printMe();
$c->printMe();


/*
0
0
0


3 asdfasfasdfasdf
3 asdfasfasdfasdf
3 asdfasfasdfasdf
*/
