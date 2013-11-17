<?php

if(isset($_POST) ) {

    var_dump($_POST);
}

<!--
<form method="post">
<input type="checkbox" name="selected_items[]" value="1" />
<input type="checkbox" name="selected_items[]" value="2" />
<input type="checkbox" name="selected_items[]" value="3" />
<input type="checkbox" name="selected_items[]" value="4" />
<input type="submit" value="submit">
</form>
-->

<form method="post">

<input type="text" name="dyn[0][name]" value="0 - chuck" />
<input type="checkbox" name="dyn[0][yep]" value="1" />

<input type="text" name="dyn[1][name]" value="1 - derp" />
<input type="checkbox" name="dyn[1][yep]" value="1" />

<input type="text" name="dyn[2][name]" value="2 - todd" />
<input type="checkbox" name="dyn[2][yep]" value="1" />

<input type="submit" value="submit">
</form>
