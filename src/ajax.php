<?php

include('connect.php');

if (isset($_POST['getQuery'])) {
    $myquery = $db->prepare('SELECT * FROM Attributes WHERE AttrName = ?');
    $myquery->bindValue(1, $_POST['getQuery']);
    $myquery->execute();
    $result = $myquery->fetchObject();
    
    echo json_encode(array("attrName"=>$result->AttrName, "attrPoints"=>"Point cost: " . $result->AttrPoints, "attrDesc"=>$result->AttrDesc));
}

?>