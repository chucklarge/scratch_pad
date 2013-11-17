<?php

for($i= 2012; $i > 1900; $i--) {
    echo sprintf("union select %d as \"key\", %d as \"value\"\n", $i, $i);
}

