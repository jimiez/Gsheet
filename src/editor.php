<?php

if (isset($_POST['attrName'])) {
    $myquery = $db->prepare('INSERT INTO Attributes VALUES 
    (?, ?, ?, ?)');

    $myquery->bindValue(1, $_POST['attrName']);
    $myquery->bindValue(2, $_POST['attrType']);
    $myquery->bindValue(3, $_POST['attrPoints']);
    $myquery->bindValue(4, $_POST['attrDesc']);
    $myquery->execute();
    echo "<br><br><b>New attribute created!</b>";
    die();
}

if (isset($_POST['skillName'])) {
    $myquery = $db->prepare('INSERT INTO Skills VALUES 
    (?, ?, ?, ?, ?)');

    $myquery->bindValue(1, $_POST['skillName']);
    $myquery->bindValue(2, $_POST['skillType']);
    $myquery->bindValue(3, $_POST['skillDiff']);
    $myquery->bindValue(4, $_POST['skillDefault']);
    $myquery->bindValue(5, $_POST['skillDesc']);
    $myquery->execute();
    echo "<br><br><b>New skill created!</b>";
    die();
}

?>
<h2>Editor</h2>
<b>Add new</b>

<form name="editType">
    <input type="radio" name="type" value="attribute" onclick="fetchForm(this)">Advantage / Disadvantage 
    <input type="radio" name="type" value="skill" onclick="fetchForm(this)">Skill<br>
</form>

<div id="fetchedForm"></div>