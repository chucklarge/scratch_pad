<?php

$s = "&#39;atlas.compass.scram_to_gavel.vintage&#39;";
var_dump($s);

$d = html_entity_decode($s, ENT_QUOTES, 'UTF-8');
var_dump($d);

