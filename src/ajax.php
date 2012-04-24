<?php

include('connect.php');

if (isset($_GET['a'])) {
    $myquery = $db->prepare('SELECT * FROM Attributes WHERE AttrName = ?');
    $myquery->bindValue(1, $_GET['a']);
    $myquery->execute();
    $result = $myquery->fetchObject();

    echo "<b>$result->AttrName</b><br><br>
    Point cost: $result->AttrPoints<br><br>
    <textarea cols='40' rows='15' readonly='readonly'>$result->AttrDesc</textarea><br><br>
    <input type='button' onClick=\"setAttrValue('$result->AttrName', '$result->AttrPoints', '$result->AttrType')\" value='Select' class='nicebutton'>";
}

if (isset($_GET['s'])) {
    $myquery = $db->prepare('SELECT * FROM Skills WHERE SkillName = ?');
    $myquery->bindValue(1, $_GET['s']);
    $myquery->execute();
    $result = $myquery->fetchObject();

    if ($result->SkillType == 'M') {
        $type = 'Mental';
    } else {
        $type = 'Physical';
    }
    
    echo "<b>$result->SkillName</b><br><br>
    $type / $result->SkillDiff<br>
    Defaults to:  $result->SkillDefault<br><br>
    <textarea cols='40' rows='14' readonly='readonly'>$result->SkillDesc</textarea><br><br>
    <input type='button' onClick=\"setSkillValue('$result->SkillName', '$result->SkillType', '$result->SkillDiff')\" value='Select' class='nicebutton'>";
}


?>

