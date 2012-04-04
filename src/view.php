<?php
include('classes.php');
include('connect.php');
session_start();

if (isset($_SESSION['isLogged'])) {
    $loggedUser = $_SESSION['loggedUser'];
} else {
    $loggedUser = null;
}

if (isset($_GET['id'])) {
    $char = $_GET['id'];
} else {
    $char = -1;
}

$myquery = $db->prepare('SELECT COUNT(*) AS n FROM Characters WHERE Char_id = ?');
$myquery->bindValue(1, $char);
$myquery->execute();
$result = $myquery->fetchObject();

if ($result->n < 1) {
    echo "No character found!";
    die();
} else {
    $character = new Character($char);
    if ($character->getStat("CharOwner") == $loggedUser) {
        $sheet = new Sheet(true);
    } else {
        $sheet = new Sheet(false);
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Gsheet - <?php echo $character->getStat('CharName') ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" type="text/css" href="style/style.css">
        <SCRIPT src="js/view.js"></SCRIPT>
    </head>

    <body onLoad="updateAll()">

        <form name="baseform" onClick="updateAll()" method="post" action="save.php">

            <table>
                <th>
                    Basic information
                </th>
                <tr>
                    <td>
                        Name
                    </td>
                    <td>
                        <input name="nameField" type="text" class="underscore" size="35" <?php $sheet->readOnly() ?> value='<?php echo $character->getStat('CharName') ?>'>
                    </td>
                </tr>
                <tr>
                    <td>
                        Appearance
                    </td>
                    <td>
                        <input name="descriptionField" type="text" class="underscore" size="35" <?php $sheet->readOnly() ?> value='<?php echo $character->getStat('CharDesc') ?>'>
                    </td>
                </tr>
                <tr>
                    <td>
                        Description
                    </td>
                    <td>
                        <input type="text" name="notesField" size='35' class="underscore" <?php $sheet->readOnly() ?> value='<?php echo $character->getStat('CharNotes') ?>'>
                    </td>
                </tr>
                <tr>
                    
            </table>

            <table>
                <tr>
                    <td>
                        Campaign
                    </td>
                    <td>
                        <select name="campaignField" <?php $sheet->disabledSelect() ?>>
                            <option></option>
                            <?php
                            $myquery = $db->prepare('SELECT Campaign_id, CampName FROM Campaigns');
                            $myquery->execute();
                            while ($result = $myquery->fetchObject()) {
                                if ($result->Campaign_id == $character->getStat('Campaign')) {
                                echo "<option value='$result->Campaign_id' SELECTED='Yes'>$result->CampName</option>";    
                                } else {
                                echo "<option value='$result->Campaign_id'>$result->CampName</option>";
                                }
                            }
                            ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        Unused points
                    </td>
                    <td>
                        <input type="text" size="3" name="unusedPointsField" class="underscore" value="<?php echo $character->getStat('UnusedPoints') ?>" <?php $sheet->readOnly() ?>>
                    </td>
                </tr>
            </table>
            
            <table>
                <th>
                    Basic attributes
                </th>
                <tr>
                    <td>
                        <h2>ST</h2>
                    </td>
                    <td>
                        <input name="stField" type="text" class="boxy" size="2" value='<?php echo $character->getStat('ST') ?>' readonly="readonly">
                    </td>
                    <?php
                    $sheet->drawButtons("st");
                    ?>
                </tr>
                <tr>
                    <td>
                        <h2>DX</h2>
                    </td>
                    <td>
                        <input name="dxField" type="text" class="boxy" size="2" value='<?php echo $character->getStat('DX') ?>' readonly="readonly">
                    </td>
                    <?php
                    $sheet->drawButtons("dx");
                    ?>
                </tr>
                <tr>
                    <td>
                        <h2>IQ</h2>
                    </td>
                    <td>
                        <input name="iqField" type="text" class="boxy" size="2" value='<?php echo $character->getStat('IQ') ?>' readonly="readonly">
                    </td>
                    <?php
                    $sheet->drawButtons("iq");
                    ?>
                </tr>
                <tr>
                    <td>
                        <h2>HT</h2> 
                    </td>
                    <td>
                        <input name="htField" type="text" class="boxy" size="2" value='<?php echo $character->getStat('HT') ?>'  readonly="readonly">
                    </td>
                    <?php
                    $sheet->drawButtons("ht");
                    ?>
                </tr>
            </table>

            <table>

                <th>
                    Basic damage
                </th>
                <tr>
                    <td>
                        Thrust
                    </td>
                    <td>
                        <input type="text" size=5 name="dmgThrustField" readonly="readonly">
                    </td>
                </tr>
                <tr>
                    <td>
                        Slash
                    </td>
                    <td>
                        <input type="text" size=5 name="dmgSlashField" readonly="readonly">
                    </td>
                </tr>
            </table>

            <table>
                <tr>
                    <td>
                        Hits taken
                    </td>
                    <td>
                        <input type="text" size=2 name="hitsTakenField" value='<?php echo $character->getStat('HitsTaken') ?>' readonly="readonly">
                        <?php
                        $sheet->drawButtons("hitsTaken");
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        Fatigue
                    </td>
                    <td>
                        <input type="text" name="fatigueField" value='<?php echo $character->getStat('Fatigue') ?>' size="2" readonly="readonly">
                        <?php
                        $sheet->drawButtons("fatigue");
                        ?>
                    </td>
                </tr>
            </table>

            <table>
                <th colspan="3">Active defences</th>
                <tr>
                    <td>
                        Dodge
                    </td>
                    <td>
                        Parry
                    </td>
                    <td>
                        Block
                    </td>
                </tr>
                <tr>
                    <td>
                        <input name="dodgeField" type="text" class="boxy" size="2" readonly="readonly">
                    </td>
                    <td>
                        <input name="parryField" type="text" class="boxy" size="2" <?php $sheet->readOnly() ?>>
                    </td>
                    <td>
                        <input name="blockField" type="text" class="boxy" size="2" <?php $sheet->readOnly() ?>>
                    </td>
                </tr>
            </table>

            <table>
                <th>
                    Movement
                </th>
                <tr>
                    <td>
                        Basic speed
                    </td>
                    <td>
                        <input type="text" name="basicSpeedField" size="2" readonly="readonly">
                    </td>
                </tr>
                <tr>
                    <td>
                        Move
                    </td>
                    <td>
                        <input type="text" name="moveField" size="2" readonly="readonly">
                    </td>
                </tr>
                <tr>
                    <td>
                        Encumbrance
                    </td>
                    <td>
                        <input type="text" name="encumbranceField" readonly="readonly" size="5">
                    </td>
                </tr>
            </table>


            <table>
                <th>
                    Advantages
                </th>
                <th>
                    Pts
                </th>

                <?php /*
                  $totalAdvantage = 0;

                  foreach ($sheet->getAttributes("adv") as $advantage) {
                  echo "<tr><td>";
                  echo $advantage->getName();
                  echo "</td><td>";
                  echo $advantage->getPoints();
                  echo "</td></tr>";
                  $totalAdvantage += $advantage->getPoints();
                  } */
                ?>
                <tr>
                    <td>
                        <a href="editor.php">Add new</a>
                    </td>
                </tr>
            </table>

            <table>
                <th>
                    Disadvantages
                </th>
                <th>
                    Pts
                </th>

                <?php
                $totalDisadvantage = 0;
                /*
                  foreach ($sheet->getAttributes("dis") as $disadvantage) {
                  echo "<tr><td>";
                  echo $disadvantage->getName();
                  echo "</td><td>";
                  echo $disadvantage->getPoints();
                  echo "</td></tr>";
                  $totalDisadvantage += $disadvantage->getPoints();
                  } */
                ?>
                <tr>
                    <td>
                        <a href="editor.php">Add new</a>
                    </td>
                </tr>
            </table>

            <table>
                <th>
                    Skill
                </th>
                <th>
                    Type
                </th>
                <th>
                    Diff
                </th>
                <th>
                    Pts
                </th>
                <th>
                    Check
                </th>

                <?php
                /*
                  $totalSkill = 0;
                  $i = 0;
                  foreach ($sheet->getSkills() as $skill) {
                  echo "<tr><td>";
                  echo "<input type=text value='" . $skill->getName() . "' size=20 readonly='readonly' class='underscore'>";
                  echo "</td><td>";
                  echo "<input type=text value='" . $skill->getType() . "' size=1 readonly='readonly' class='underscore' name='skill" . $i . "type'>";
                  echo "</td><td>";
                  echo "<input type=text value='" . $skill->getDifficulty() . "' size=5 readonly='readonly' class='underscore' name='skill" . $i . "diff'>";
                  echo "</td><td>";
                  echo "<input type=text value='" . $skill->getPoints() . "' size=1 readonly='readonly' class='underscore' name='skill" . $i . "pts'>";
                  echo "</td><td>";
                  echo "<input type='text' value='0' size='1' class='underscore'name='skill" . $i . "result'>";
                  $i++;
                  $totalSkill += $skill->getPoints();
                  }

                 */
                ?>

            </table>

            <table>
                <th>
                    Item
                </th>
                <th>
                    Value
                </th>
                <th>
                    Weigth
                </th>

                <?php
                /*
                  foreach ($sheet->getItems() as $item) {
                  echo "<tr><td><input type='text' size=30 class='underscore' value='" . $item->getName() . "'";
                  $sheet->readOnly();
                  echo "</td>";
                  echo "<td><input type='text' size=1 class='underscore' value='" . $item->getValue() . "'";
                  $sheet->readOnly();
                  echo "</td>";
                  echo "<td><input type='text' size=1 class='underscore' value='" . $item->getWeight() . "'";
                  $sheet->readOnly();
                  echo "</td></tr>";
                  } */
                
                
                
                ?>
            </table> 
            <input type="submit" name="saveForm">
            <input type="hidden" name="charID" value="<?php echo $character->getStat('Char_id') ?>">
        </form>
    </body>
</html>
