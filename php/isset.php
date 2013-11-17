<?php


$a = array(
    'users' => array(
        'AAA' => 'aaa',
        'BBB',
    ),
);


if (isset($a['users']['AAA'])) {
    echo "AAA\n";
}

if (in_array('BBB', $a['users']) && isset($a['users']['BBB'])) {
    echo "BBB\n";
}


if (in_array('CCC', $a['users']) && isset($a['users']['BBB'])) {
    echo "BBB\n";
}
