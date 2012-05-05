<?php


?>
<h2>Editor</h2>
<b>Add new</b>

<form name="editType">
    <input type="radio" name="type" value="advantage" onclick="fetchForm(this)">Advantage / Disadvantage 
    <input type="radio" name="type" value="skill" onclick="fetchForm(this)">Skill<br>
</form>

<div id="fetchedForm"></div>