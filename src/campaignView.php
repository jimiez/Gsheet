<?php
include('session.php');
include('connect.php');

if (isset($_GET['id'])) {
    $camp = $_GET['id'];
} else {
    $camp = -1;
}

if (isset($_POST['saveCamp'])) {
    $myquery = $db->prepare('UPDATE Campaigns SET 
    CampName = ?, 
    CampNotes = ?, 
    CampPoints = ? WHERE Campaign_id = ?');

    $myquery->bindValue(1, $_POST['campName']);
    $myquery->bindValue(2, $_POST['campNotes']);
    $myquery->bindValue(3, $_POST['campPoints']);
    $myquery->bindValue(4, $_POST['campId']);
    $myquery->execute();
}

$myquery = $db->prepare('SELECT COUNT(*) AS n FROM Campaigns WHERE Campaign_id = ?');
$myquery->bindValue(1, $camp);
$myquery->execute();
$result = $myquery->fetchObject();

if ($result->n < 1) {
    echo "No campaign found!";
    die();
} else {
    $myquery = $db->prepare('SELECT * FROM Campaigns WHERE Campaign_id = ?');
    $myquery->bindValue(1, $camp);
    $myquery->execute();
    $result = $myquery->fetchObject();

    $campName = $result->CampName;
    $campPoints = $result->CampPoints;
    $campNotes = $result->CampNotes;
    $campOwner = $result->CampOwner;
}

if ($campOwner != $_SESSION['loggedUser']) {
    echo "You don't have the rights to view this campaign!";
    die();
}


?>

<html>
    <head>
        <title>Gsheet - Characaters</title>
        <link rel="stylesheet" type="text/css" href="style/style.css">
    </head>
    <body>
        <form name="campaignView" method="post" action="<?php $_SERVER['PHP_SELF'] ?>">
            <?php
            echo "<input type='text' value='$campName' size='20' name='campName'><br>";
            ?>
            <br>
            <table border="1">

                <tr>
                    <?php
                    $myquery = $db->prepare('SELECT * FROM Characters WHERE Campaign = ?');
                    $myquery->bindValue(1, $camp);
                    $myquery->execute();

                    while ($result = $myquery->fetchObject()) {
                        echo "<td align='middle' width='200'><u><a href='view.php?id=" . $result->Char_id . "' target='_blank'>$result->CharName ($result->CharOwner)</a></u><br>";
                        echo "ST: $result->ST<br>";
                        echo "DX: $result->DX<br>";
                        echo "IQ: $result->IQ<br>";
                        echo "HT: $result->HT<br>";
                        echo "Hits taken: $result->HitsTaken<br>";
                        echo "Fatigue: $result->Fatigue<br>";
                        echo "<hr width='100'>";

                        $mysubquery = $db->prepare('SELECT Attr_name FROM AttributeList WHERE CharAttr_id = ?');
                        $mysubquery->bindValue(1, $result->Char_id);
                        $mysubquery->execute();

                        while ($result = $mysubquery->fetchObject()) {
                            if ($result->Attr_name != "") {
                                echo $result->Attr_name . "<br>";
                            }
                        }
                        echo "</td>";
                    }
                    ?>
                </tr>
            </table>

            <p>Campaign notes</p>
            <textarea name="campNotes" rows="12" cols="35"><?php echo $campNotes ?></textarea><br>
            <p>Campaign total points</p>
            <input type="text" name="campPoints" value="<?php echo $campPoints ?>"><br>
            <input type="hidden" value="<?php echo $camp ?>" name="campId">
            <input type="submit" value="Save" name="saveCamp">
        </form>
    </body>
</html>