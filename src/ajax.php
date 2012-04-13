<?php

include('connect.php');

if (isset($_POST['getQuery'])) {
    $myquery = $db->prepare('SELECT * FROM Attributes WHERE AttrName = ?');
    $myquery->bindValue(1, $_POST['getQuery']);
    $myquery->execute();
    $result = $myquery->fetchObject();
    
    echo json_encode(array("attrName"=>$result->AttrName, "attrPoints"=>"Point cost: " . $result->AttrPoints, "attrDesc"=>$result->AttrDesc));
}

if (isset($_POST['getCamp'])) {
    $myquery = $db->prepare('SELECT CampName, CampDesc, (SELECT count(CharName) FROM Characters WHERE Campaign = ?) as PlayerCount FROM Campaigns WHERE Campaign_id = ?');
    $myquery->bindValue(1, $_POST['getCamp']);
    $myquery->bindValue(2, $_POST['getCamp']);
    $myquery->execute();
    $result = $myquery->fetchObject();
    
    echo json_encode(array("campName"=>$result->CampName, "campDesc"=>$result->CampDesc, "campPlayers"=>"Players: " . $result->PlayerCount));
}
?>