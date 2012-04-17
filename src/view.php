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
    $activeDef = $character->getDef('active');
    $passiveDefPD = $character->getDef('passivePD');
    $passiveDefDR = $character->getDef('passiveDR');
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
            <table cellpadding="3" cellspacing="0" border="1">

                <tr>

                    <td colspan="2">

                        <table>
                            <th colspan="2">Basic information</th>
                            <tr>
                                <td>Name</td>
                                <td><input name="nameField" type="text" class="underscore" size="35" <?php $sheet->readOnly() ?> value='<?php echo $character->getStat('CharName') ?>'></td>
                            </tr>
                            <tr>
                                <td>Appearance</td>
                                <td><input name="descriptionField" type="text" class="underscore" size="35" <?php $sheet->readOnly() ?> value='<?php echo $character->getStat('CharDesc') ?>'></td>
                            </tr>
                            <tr>
                                <td>Campaign</td>
                                <td>
                                    <select name="campaignField" <?php $sheet->disabledSelect() ?> style="width: 150px">
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

                        </table>

                    </td>

                    <td>

                        <table>
                            <tr>
                                <td>Summary of points here</td>
                            </tr>
                            <tr>
                                <td>Unused points</td>
                                <td><input type="text" size="3" name="unusedPointsField" class="underscore" value="<?php echo $character->getStat('UnusedPoints') ?>" <?php $sheet->readOnly() ?>></td>
                            </tr>
                        </table>

                    </td>

                </tr>

                <tr>

                    <td rowspan="2">

                        <table>
                            <th colspan="2">Basic attributes</th>
                            <tr>
                                <td><h2>ST</h2></td>
                                <td>
                                    <input name="stField" type="text" class="boxy" size="2" value='<?php echo $character->getStat('ST') ?>' readonly="readonly">
                                </td>
                                <?php
                                $sheet->drawButtons("st");
                                ?>
                            </tr>
                            <tr>
                                <td><h2>DX</h2></td>
                                <td>
                                    <input name="dxField" type="text" class="boxy" size="2" value='<?php echo $character->getStat('DX') ?>' readonly="readonly">
                                </td>
                                <?php
                                $sheet->drawButtons("dx");
                                ?>
                            </tr>
                            <tr>
                                <td><h2>IQ</h2></td>
                                <td>
                                    <input name="iqField" type="text" class="boxy" size="2" value='<?php echo $character->getStat('IQ') ?>' readonly="readonly">
                                </td>
                                <?php
                                $sheet->drawButtons("iq");
                                ?>
                            </tr>
                            <tr>
                                <td><h2>HT</h2></td>
                                <td>
                                    <input name="htField" type="text" class="boxy" size="2" value='<?php echo $character->getStat('HT') ?>'  readonly="readonly">
                                </td>
                                <?php
                                $sheet->drawButtons("ht");
                                ?>
                            </tr>
                        </table>

                    </td>

                    <td>

                        <table>

                            <th>Basic damage</th>
                            <tr>
                                <td>Thrust</td>
                                <td><input type="text" size=5 name="dmgThrustField" readonly="readonly"></td>
                            </tr>
                            <tr>
                                <td>Slash</td>
                                <td><input type="text" size=5 name="dmgSlashField" readonly="readonly"></td>
                            </tr>
                        </table>

                    </td>

                    <td rowspan="5">

                        <table>
                            <th>Item</th>
                            <th>Value</th>
                            <th>Weigth</th>

                            <?php
                            for ($i = 0; $i < 21; $i++) {
                                ?>
                                <tr>
                                    <td><input type='text' size=20 class='underscore' name="itemName[]"></td>
                                    <td><input type='text' size=1 class='underscore' name="itemValue[]"></td>
                                    <td><input type='text' size=1 class='underscore' name="itemWeight[]"></td>
                                </tr>
                                <?php
                            }
                            ?>
                            <tr>
                                <td>Total:</td>
                                <td><input type='text' size=1 class='underscore' name="totalValue"></td>
                                <td><input type='text' size=1 class='underscore' name="totalWeight"></td>
                            </tr>
                        </table> 

                    </td>
                </tr>

                <tr>

                    <td>

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

                    </td>
                </tr>

                <tr>

                    <td>

                        <table>
                            <th colspan="2">Movement</th>
                            <tr>
                                <td>Basic speed</td>
                                <td>
                                    <input type="text" name="basicSpeedField" size="2" readonly="readonly">
                                </td>
                            </tr>
                            <tr>
                                <td>Move</td>
                                <td>
                                    <input type="text" name="moveField" size="2" readonly="readonly">
                                </td>
                            </tr>
                            <tr>
                                <td>Encumbrance</td>
                                <td>
                                    <input type="text" name="encumbranceField" readonly="readonly" size="5">
                                </td>
                            </tr>
                        </table>

                    </td>

                    <td rowspan="2">
                        <table>
                            <th colspan="3">
                                Passive defense
                            </th>
                            <tr>
                                <td>Slot</td>
                                <td>PD</td>
                                <td>DR</td>
                            </tr>
                            <tr>
                                <td>Head</td>
                                <td>
                                    <input name="pasPD[]" type="text" size="1" <?php
                                    $sheet->readOnly();
                                    echo "value='$passiveDefPD[0]'"
                                    ?>>
                                </td>
                                <td>
                                    <input name="pasDR[]" type="text" size="1" <?php
                                           $sheet->readOnly();
                                           echo "value='$passiveDefDR[0]'"
                                    ?>>
                                </td>
                            </tr>
                            <tr>
                                <td>Body</td>
                                <td>
                                    <input name="pasPD[]" type="text" size="1" <?php
                                           $sheet->readOnly();
                                           echo "value='$passiveDefPD[1]'"
                                    ?>>
                                </td>
                                <td>
                                    <input name="pasDR[]" type="text" size="1" <?php
                                           $sheet->readOnly();
                                           echo "value='$passiveDefDR[1]'"
                                    ?>>
                                </td>
                            </tr>
                            <tr>
                                <td>Arms</td>
                                <td>
                                    <input name="pasPD[]" type="text" size="1" <?php
                                           $sheet->readOnly();
                                           echo "value='$passiveDefPD[2]'"
                                    ?>>
                                </td>
                                <td>
                                    <input name="pasDR[]" type="text" size="1" <?php
                                           $sheet->readOnly();
                                           echo "value='$passiveDefDR[2]'"
                                    ?>>
                                </td>
                            </tr>
                            <tr>
                                <td>Legs</td>
                                <td>
                                    <input name="pasPD[]" type="text" size="1" <?php
                                           $sheet->readOnly();
                                           echo "value='$passiveDefPD[3]'"
                                    ?>>
                                </td>
                                <td>
                                    <input name="pasDR[]" type="text" size="1" <?php
                                           $sheet->readOnly();
                                           echo "value='$passiveDefDR[3]'"
                                    ?>>
                                </td>
                            </tr>
                            <tr>
                                <td>Hands</td>
                                <td>
                                    <input name="pasPD[]" type="text" size="1" <?php
                                           $sheet->readOnly();
                                           echo "value='$passiveDefPD[4]'"
                                    ?>>
                                </td>
                                <td>
                                    <input name="pasDR[]" type="text" size="1" <?php
                                           $sheet->readOnly();
                                           echo "value='$passiveDefDR[4]'"
                                    ?>>
                                </td>
                            </tr>
                            <tr>
                                <td>Feet</td>
                                <td>
                                    <input name="pasPD[]" type="text" size="1" <?php
                                           $sheet->readOnly();
                                           echo "value='$passiveDefPD[5]'"
                                    ?>>
                                </td>
                                <td>
                                    <input name="pasDR[]" type="text" size="1" <?php
                                           $sheet->readOnly();
                                           echo "value='$passiveDefDR[5]'"
                                    ?>>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Total
                                </td>
                                <td>
                                    <input name="TotalPD" type="text" size="1" readonly="readonly">
                                <td>
                                    <input name="TotalDR" type="text" size="1" readonly="readonly">
                                </td>
                            </tr>
                        </table>
                    </td>

                </tr>
                <tr>

                    <td>

                        <table>
                            <th colspan="3">Active defences</th>
                            <tr>
                                <td>Dodge</td>
                                <td>Parry</td>
                                <td>Block</td>
                            </tr>
                            <tr>
                                <td>
                                    <input name="dodgeField" type="text" class="boxy" size="2" readonly="readonly">
                                </td>
                                <td>
                                    <input name="parryField" type="text" value='<?php echo $activeDef[0] ?>' class="boxy" size="2" <?php $sheet->readOnly() ?>>
                                </td>
                                <td>
                                    <input name="blockField" type="text" value='<?php echo $activeDef[1] ?>' class="boxy" size="2" <?php $sheet->readOnly() ?>>
                                </td>
                            </tr>
                        </table>

                    </td>

                </tr>

                <tr>

                    <td colspan="2">

                        <table>
                            <th colspan="3">Equipped weapons</th>
                            <tr>
                                <td>Name</td>
                                <td>Dmg<br>type</td>
                                <td>Dmg<br>amount</td>
                                <td>Weapon notes</td>
                            </tr>            
                            <?php
                            for ($i = 0; $i < 3; $i++) {
                                ?>
                                <tr>
                                    <td><input type="text" size="30" name="eqWpnName[]" <?php $sheet->readOnly() ?> class="underscore"></td>
                                    <td><input type="text" size="2" name="eqWpnDmgType[]" <?php $sheet->readOnly() ?> class="underscore"></td>
                                    <td><input type="text" size="2" name="eqWpnDmg[]" <?php $sheet->readOnly() ?> class="underscore"></td>
                                    <td><input type="text" size="30" name="eqWpnNotes[]" <?php $sheet->readOnly() ?> class="underscore"></td>
                                </tr>
                                <?php
                            }
                            ?>
                        </table>

                    </td>

                </tr>

                <tr>

                    <td>

                        <table>
                            <th>Advantages</th>
                            <th>Pts</th>

                            <?php
                            $advantages = $character->getAttributes("adv");

                            for ($i = 0; $i < 8; $i++) {
                                if ($i < sizeof($advantages)) {
                                    $adv = $advantages[$i];
                                    echo "<tr><td>";
                                    echo "<input type='text' value='" . $adv->getName() . "' size='25' " . $sheet->readOnly() . " class='underscore' name='attributeName[]' onClick='return openSelector(this)'>";
                                    echo "</td><td>";
                                    echo "<input type='text' value='" . $adv->getPoints() . "' size='2' " . $sheet->readOnly() . " readonly='readonly' class='underscore' name='attributePoints[]'>";
                                    echo "</td></tr>";
                                } else {
                                    ?>
                            <tr>
                                <td><input type='text' size='25' class='underscore' name='attributeName[]' onClick="return openSelector(this)"></td>
                                <td><input type='text' size='2' class='underscore' name='attributePoints[]'></td>   
                            </tr>
                            <?php
                                   }
                            }
                            ?>
                        </table>

                    </td>

                    <td>

                        <table>
                            <th>Disadvantage</th>
                            <th>Pts</th>
                            
                        </table>

                    </td>

                    <td>

                        <table>
                            <th>Quirks</th>
                            <?php
                            for ($i = 0; $i < 5; $i++) {
                                ?>
                                <tr><td><input type="text" size="30" name="quirks[]>" class="underscore"></td></tr>
                                        <?php
                                    }
                                    ?>                            
                        </table>

                    </td>

                </tr>


                <table>
                    <th>Skill</th>
                    <th>Type</th>
                    <th>Diff</th>
                    <th>Pts</th>
                    <th>Check</th>

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

            </table>
            <input type="submit" name="saveForm">
            <input type="hidden" name="charID" value="<?php echo $character->getStat('Char_id') ?>">
        </form>
    </body>
</html>
