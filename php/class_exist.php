<?php

class Derp01 {
    public function whoa() {
        print "1\n";
    }
}

class Derp02 {
    public function whoa() {
        print "2\n";
    }
}

class Derp03 {
    public function whoa() {
        print "3\n";
    }
}

$versions = array(
    0, 0.0, .0, .1, .2, .3, 1, 1.1, 10
);

$versions = array(
    0.1, 0.2, 0.3, 0.4, 0.5, 0.6, 1, 1.1, 10
);



foreach ($versions as $analyticsVersion) {
    if ($analyticsVersion > 0.0 && $analyticsVersion <= 0.3 ) {
        $analyticsVersion = 0.3;
    } else if ($analyticsVersion === 0.4 ) {
        // do nothing
    } else if ($analyticsVersion >= 0.5 ) {
        $analyticsVersion = 0.5;
    } else {
        throw new RuntimeException("Invalid IOS analytics version " . $analyticsVersion);
    }

print $analyticsVersion ."\n";


    //$num = number_format($analyticsVersion, 1, '', '');
    //print $num . "\n";
    //$class = 'Derp'.$num;
    //if (class_exists($class)) {
        //$c = new $class();
        //$c->whoa();
    //} else {
        //print "no\n";
    //}
}
