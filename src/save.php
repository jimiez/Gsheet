<?php
include('connect.php');


// reads and combines the passive defenses as string
$pdpd = $_POST['pasPD'][0];
$pddr = $_POST['pasDR'][0];
for ($i = 1; $i < sizeof($_POST['pasPD']); $i++) {
    $pdpd = $pdpd . "|" .$_POST['pasPD'][$i];
    $pddr = $pddr . "|" .$_POST['pasDR'][$i];
}

for ($i = 0; $i < sizeof($_POST['itemName']); $i++) {
    echo $_POST['itemName'][$i] . " - " . $_POST['itemValue'][$i] . " - " . $_POST['itemWeight'][$i] . "<br>";
}


//$myquery = $db->prepare('UPDATE Characters SET Campaign =  ?,
//CharName =  ?,
//CharDesc =  ?,
//HitsTaken = ?,
//Fatigue =  ?,
//ST =  ?,
//DX =  ?,
//IQ =  ?,
//HT =  ?,
//ActiveDefense =  ?,
//PassiveDefensePD =  ?,
//PassiveDefenseDR =  ?,
//CharNotes =  ?,
//UnusedPoints =  ? WHERE Char_id = ?');
//
//$myquery->bindValue(1, $_POST['campaignField']);
//$myquery->bindValue(2, $_POST['nameField']);
//$myquery->bindValue(3, $_POST['descriptionField']);
//$myquery->bindValue(4, $_POST['hitsTakenField']);
//$myquery->bindValue(5, $_POST['fatigueField']);
//$myquery->bindValue(6, $_POST['stField']);
//$myquery->bindValue(7, $_POST['dxField']);
//$myquery->bindValue(8, $_POST['iqField']);
//$myquery->bindValue(9, $_POST['htField']);
//$activeDefense = $_POST['parryField'] . "|" . $_POST['blockField'];
//$myquery->bindValue(10, $activeDefense);

//$myquery->bindValue(11, $passiveDefense);
//$myquery->bindValue(12, $_POST['notesField']);
//$myquery->bindValue(13, $_POST['unusedPointsField']);
//$myquery->bindValue(14, $_POST['charID']);
//
//$myquery->execute();
//header("Location: view.php?id=".$_POST['charID']);



?>
