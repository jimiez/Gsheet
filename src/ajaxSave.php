<?php

include('connect.php');

    $myquery = $db->prepare('UPDATE Attributes SET
        AttrName = ?,
        AttrType = ?,
        AttrPoints = ?,
        AttrDesc = ?
        WHERE AttrName = ?');
    $myquery->bindValue(1, $_POST['attrName']);
    $myquery->bindValue(2, $_POST['attrType']);
    $myquery->bindValue(3, $_POST['attrPoints']);
    $myquery->bindValue(4, $_POST['attrDesc']);
    $myquery->bindValue(5, $_POST['originalName']);

    $myquery->execute();
    
    echo "Whee";

?>
