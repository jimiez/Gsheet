<?php

include('connect.php');

// Ensin tallennetaan hahmon perusominaisuudet

$myquery = $db->prepare('UPDATE Characters SET 
Campaign =  ?,
CharName =  ?,
CharDesc =  ?,
HitsTaken = ?,
Fatigue =  ?,
ST =  ?,
DX =  ?,
IQ =  ?,
HT =  ?,
ActiveDefense =  ?,
PassiveDefensePD =  ?,
PassiveDefenseDR =  ?,
Quirks = ?,
CharNotes =  ?,
UnusedPoints =  ? WHERE Char_id = ?');

$myquery->bindValue(1, $_POST['campaignField']);
$myquery->bindValue(2, $_POST['nameField']);
$myquery->bindValue(3, $_POST['descriptionField']);
$myquery->bindValue(4, $_POST['hitsTakenField']);
$myquery->bindValue(5, $_POST['fatigueField']);
$myquery->bindValue(6, $_POST['stField']);
$myquery->bindValue(7, $_POST['dxField']);
$myquery->bindValue(8, $_POST['iqField']);
$myquery->bindValue(9, $_POST['htField']);

$activeDefense = $_POST['parryField'] . "|" . $_POST['blockField'];

$myquery->bindValue(10, $activeDefense);

// Lukee ja yhdistää stringiksi
$pdpd = $_POST['pasPD'][0];
$pddr = $_POST['pasDR'][0];
for ($i = 1; $i < sizeof($_POST['pasPD']); $i++) {
    $pdpd = $pdpd . "|" . $_POST['pasPD'][$i];
    $pddr = $pddr . "|" . $_POST['pasDR'][$i];
}

$myquery->bindValue(11, $pdpd);
$myquery->bindValue(12, $pddr);

$quirks = $_POST['quirks'][0];
for ($i = 1; $i < sizeof($_POST['quirks']); $i++) {
    $quirks = $quirks . "|" . $_POST['quirks'][$i];
}

$myquery->bindValue(13, $quirks);
$myquery->bindValue(14, $_POST['notesField']);
$myquery->bindValue(15, $_POST['unusedPointsField']);
$myquery->bindValue(16, $_POST['charID']);

$myquery->execute();

// Tavarat inventaarioon

try {
    $db->beginTransaction();

    for ($i = 0; $i < sizeof($_POST['itemName']); $i++) {

        $myquery = $db->prepare("REPLACE INTO Items VALUES (?, ?, ?, ?, ?, ?)");

        $myquery->bindValue(1, $_POST['itemId'][$i]);
        $myquery->bindValue(2, $_POST['charID']);
        $myquery->bindValue(3, $_POST['itemName'][$i]);
        $myquery->bindValue(4, $_POST['itemType'][$i]);
        $myquery->bindValue(5, $_POST['itemWeight'][$i]);
        $myquery->bindValue(6, $_POST['itemValue'][$i]);
        $myquery->execute();
    }

    $db->commit();
} catch (PDOException $e) {
    $db->rollBack();
    die("ERROR: " . $e->getMessage());
}

// Advantages

try {
    $db->beginTransaction();

    for ($i = 0; $i < sizeof($_POST['advantageId']); $i++) {

        $myquery = $db->prepare("REPLACE INTO AttributeList VALUES (?, ?, ?, ?, ?)");
        $myquery->bindValue(1, $_POST['advantageId'][$i]);
        $myquery->bindValue(2, $_POST['charID']);
        $myquery->bindValue(3, $_POST['advantageName'][$i]);
        $myquery->bindValue(4, $_POST['advantagePoints'][$i]);
        $myquery->bindValue(5, 'A');

        $myquery->execute();
    }

    $db->commit();
} catch (PDOException $e) {
    $db->rollBack();
    die("ERROR: " . $e->getMessage());
}

// Disadvantages


try {
    $db->beginTransaction();


    for ($i = 0; $i < sizeof($_POST['disadvantageId']); $i++) {

        $myquery = $db->prepare("REPLACE INTO AttributeList VALUES (?, ?, ?, ?, ?)");
        $myquery->bindValue(1, $_POST['disadvantageId'][$i]);
        $myquery->bindValue(2, $_POST['charID']);
        $myquery->bindValue(3, $_POST['disadvantageName'][$i]);
        $myquery->bindValue(4, $_POST['disadvantagePoints'][$i]);
        $myquery->bindValue(5, 'D');

        $myquery->execute();
    }



    $db->commit();
} catch (PDOException $e) {
    $db->rollBack();
    die("ERROR: " . $e->getMessage());
}

// Equipped weapons

try {
    $db->beginTransaction();

    for ($i = 0; $i < sizeof($_POST['eqWpnName']); $i++) {

        $myquery = $db->prepare("REPLACE INTO EquippedWeapons VALUES (?, ?, ?, ?, ?, ?)");
        $myquery->bindValue(1, $_POST['eqWpnId'][$i]);
        $myquery->bindValue(2, $_POST['charID']);
        $myquery->bindValue(3, $_POST['eqWpnName'][$i]);
        $myquery->bindValue(4, $_POST['eqWpnDmgType'][$i]);
        $myquery->bindValue(5, $_POST['eqWpnDmg'][$i]);
        $myquery->bindValue(6, $_POST['eqWpnNotes'][$i]);
        $myquery->execute();
    }

    $db->commit();
} catch (PDOException $e) {
    $db->rollBack();
    die("ERROR: " . $e->getMessage());
}

header("Location: view.php?id=" . $_POST['charID']);
?>
