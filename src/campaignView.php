<?php
include('classes.php');
include('connect.php');
session_start();
if (isset($_GET['id'])) {
    $camp = $_GET['id'];
} else {
    $camp = -1;
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
    $campDesc = $result->CampDesc;
}
?>

<html>
    <head>
        <title>Gsheet - Characaters</title>
        <link rel="stylesheet" type="text/css" href="style/style.css">
    </head>
    <body>
        <div id="main">
            <?php
            echo "<b>$campName</b><br>";
            echo "<p>$campDesc</b><br><br>";
            ?>

            <table border="1">
                <tr>
                    <?php
                    $myquery = $db->prepare('SELECT * FROM Characters WHERE Campaign = ?');
                    $myquery->bindValue(1, $camp);
                    $myquery->execute();

                    while ($result = $myquery->fetchObject()) {
                        echo "<td align='middle' width='200'><u>$result->CharName ($result->CharOwner)</u><br>";
                        echo "ST: $result->ST<br>";
                        echo "DX: $result->DX<br>";
                        echo "IQ: $result->IQ<br>";
                        echo "HT: $result->HT<br>";
                        echo "Hits taken: $result->HitsTaken<br>";
                        echo "Fatigue: $result->Fatigue<br>";
                        echo "<hr width='100'>";
                        echo "</td>";
                        
                        
                        $mysubquery = $db->prepare('SELECT Attr_name FROM AttributeList WHERE CharAttr_id = ?');
                        $mysubquery->bindValue(1, $result->Char_id);
                        $mysubquery->execute();
                        
                        $result = $mysubquery;
                    }
                    ?>
                </tr>
            </table>





        </div>
    </body>
</html>