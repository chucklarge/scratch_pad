<?php

$path = 'statuses/retweet/:id/:derp';

$replace = array(
    ':id'   => 23345245,
    ':derp' => 'aasdfasdf',
);


echo $path . "\n";
foreach ($replace as $key => $value) {
    $path = str_replace($key, $value, $path);
}
echo $path . "\n";
