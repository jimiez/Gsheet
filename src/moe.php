<?php

include('connect.php');

$q = $_GET['q'];

if (isset($q)) {
    $myquery = $db->prepare('SELECT * FROM Attributes WHERE AttrName = ?');
    $myquery->bindValue(1, $q);
    $myquery->execute();
    $result = $myquery->fetchObject();

    echo "<b>$result->AttrName</b><br><br>
    Point cost: $result->AttrPoints <br><br>
    <textarea cols='40' rows='15' readonly='readonly'>$result->AttrDesc</textarea><br><br>
    <input type='button' onClick=\"setValue(document.selector.selected.value)\" value='Select' class='nicebutton'>";
}
?>

