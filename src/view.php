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
    $quirks = $character->getQuirks();
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
        <a href="index.php">Return to main</a>
        <form name="baseform" onClick="updateAll()" method="post" action="save.php">
            <table cellpadding="3" cellspacing="0" border="1">
                <tr>
                    <td colspan="2">
                        <table>
                            <th colspan="2">Basic information</th>
                            <tr>
                                <td>Name</td>
                                <td><input name="nameField" type="text" class="underscore" size="55" <?php $sheet->readOnly() ?> value='<?php echo $character->getStat('CharName') ?>'></td>
                            </tr>
                            <tr>
                                <td>Appearance</td>
                                <td><input name="descriptionField" type="text" class="underscore" size="55" <?php $sheet->readOnly() ?> value='<?php echo $character->getStat('CharDesc') ?>'></td>
                            </tr>
                            <tr>
                                <td>Campaign</td>
                                <td>
                                    <select name="campaignField" <?php $sheet->disabledSelect() ?> style="width: 250px">

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
                            <th>Point summary</th>
                            <tr>
                                <td>Basic attributes</td>
                                <td><input type="text" size="3" name="attributeTotalField" class="underscore"  readonly="readonly"></td>

                                <td>Skills</td>
                                <td><input type="text" size="3" name="skillTotalField" class="underscore" readonly="readonly"></td>
                            </tr>
                            <tr>
                                <td>Advantages</td>
                                <td><input type="text" size="3" name="advantageTotalField" class="underscore" readonly="readonly"></td>

                                <td>Disadvantages</td>
                                <td><input type="text" size="3" name="disadvantageTotalField" class="underscore" readonly="readonly"></td>
                            </tr>
                            <tr>
                                <td>Total points</td>
                                <td><input type="text" size="3" name="totalPointsField" class="underscore" readonly="readonly"></td>

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
                    <td rowspan="4">
                        <table>
                            <th>Skill</th>
                            <th>Type</th>
                            <th>Diff</th>
                            <th>Pts</th>
                            <th>Check</th>

                            <?php
                            for ($i = 0; $i < 17; $i++) {
                                ?>
                                <tr>
                                    <td><input type=text size=20 readonly='readonly' class='underscore' name='skillName[]' <?php $sheet->writeIfOwner("onclick=\"return openSelector(this, 'skill')\"") ?>></td>
                                    <td><input type=text size=1 readonly='readonly' class='underscore' name='skillType[]'></td>
                                    <td><input type=text size=5 readonly='readonly' class='underscore' name='skillDiff[]'></td>
                                    <td><input type=text size=1 class='underscore' name='skillPts[]' <?php $sheet->readOnly() ?>></td>
                                    <td><input type=text size=1 readonly='readonly' class='underscore' name='skillCheck[]'></td>
                                </tr>

                                <?php
                            }
                            ?>

                        </table>
                    </td>
                </tr>
                <tr>
                    <td class="middle">
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

                    <td class="middle">

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
                            $equippedWeapons = $character->getEquippedWeapons();
                            for ($i = 0; $i < 4; $i++) {
                                if ($i < sizeof($equippedWeapons)) {
                                    $eqWpn = $equippedWeapons[$i];
                                    ?>
                                    <tr>
                                        <td><input type="hidden" name="eqWpnId[]" value="<?php echo $eqWpn->getId() ?>">
                                            <input type="text" value="<?php echo $eqWpn->getName() ?>" size="20" name="eqWpnName[]" <?php $sheet->readOnly() ?> class="underscore"></td>
                                        <td><input type="text" value="<?php echo $eqWpn->getDamageType() ?>" size="4" name="eqWpnDmgType[]" <?php $sheet->readOnly() ?> class="underscore"></td>
                                        <td><input type="text" value="<?php echo $eqWpn->getDamageAmount() ?>" size="2" name="eqWpnDmg[]" <?php $sheet->readOnly() ?> class="underscore"></td>
                                        <td><input type="text" value="<?php echo $eqWpn->getNotes() ?>" size="30" name="eqWpnNotes[]" <?php $sheet->readOnly() ?> class="underscore"></td>
                                    </tr>

                                    <?php
                                } else {
                                    ?>
                                    <tr>
                                        <td><input type="hidden" name="eqWpnId[]">
                                            <input type="text" size="20" name="eqWpnName[]" <?php $sheet->readOnly() ?> class="underscore"></td>
                                        <td><input type="text" size="4" name="eqWpnDmgType[]" <?php $sheet->readOnly() ?> class="underscore"></td>
                                        <td><input type="text" size="2" name="eqWpnDmg[]" <?php $sheet->readOnly() ?> class="underscore"></td>
                                        <td><input type="text" size="30" name="eqWpnNotes[]" <?php $sheet->readOnly() ?> class="underscore"></td>
                                    </tr>
                                    <?php
                                }
                            }
                            ?>
                        </table>

                    </td>
                    <td>
                        <table>
                            <th>Quirks</th>
                            <?php
                            for ($i = 0; $i < 5; $i++) {
                                ?>
                                <tr><td><input type="text" size="40" name="quirks[]>" value="<?php echo $quirks[$i] ?>" class="underscore"></td></tr>
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
                                    ?>
                                    <tr><td>
                                            <input type="hidden" value="<?php echo $adv->getId() ?>" name="advantageId[]">
                                            <input type='text' <?php echo "value='" . $adv->getName() . "'"; $sheet->readOnly() ?> size='25' class='underscore' name='advantageName[]' <?php $sheet->writeIfOwner("ondblClick=\"return openSelector(this, 'advantage')\"") ?>>
                                        </td><td>
                                            <input type='text' <?php echo "value='" . $adv->getPoints() . "'"; $sheet->readOnly() ?> size='2' class='underscore' name='advantagePoints[]'>
                                        </td></tr>
                                    <?php
                                } else {
                                    ?> 
                                    <tr><td><input type="hidden" name="advantageId[]">
                                            <input type='text' size='25' class='underscore' <?php $sheet->readOnly() ?> name='advantageName[]' <?php $sheet->writeIfOwner("ondblClick=\"return openSelector(this, 'advantage')\"")
                                    ?>></td>
                                        <td><input type='text' size='2' class='underscore' name='advantagePoints[]' <?php $sheet->readOnly() ?>></td>   
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

                            <?php
                            $disadvantages = $character->getAttributes("disadv");

                            for ($i = 0; $i < 8; $i++) {
                                if ($i < sizeof($disadvantages)) {
                                    $dadv = $disadvantages[$i];
                                    ?>
                                    <tr><td>
                                            <input type="hidden" value="<?php echo $dadv->getId() ?>" name="disadvantageId[]">
                                            <input type='text' <?php echo "value='" . $dadv->getName() . "'"; $sheet->readOnly() ?> size='25'  class='underscore' name='disadvantageName[]' <?php $sheet->writeIfOwner("ondblclick=\"return openSelector(this, 'disadvantage')\"") ?>>
                                        </td><td>
                                            <input type='text' <?php echo "value='" . $dadv->getPoints() . "'"; $sheet->readOnly() ?> size='2' class='underscore' name='disadvantagePoints[]'>
                                        </td></tr>
                                    <?php
                                } else {
                                    ?>
                                    <tr>
                                        <td><input type="hidden" name="disadvantageId[]"><input type='text' size='25' <?php $sheet->readOnly() ?>class='underscore' name='disadvantageName[]' <?php $sheet->writeIfOwner("ondblClick=\"return openSelector(this, 'disadvantage')\"") ?>></td>
                                        <td><input type='text' size='2' class='underscore' name='disadvantagePoints[]' <?php $sheet->readOnly() ?>></td>   
                                    </tr>
                                    <?php
                                }
                            }
                            ?>

                        </table>
                    </td>
                    <td rowspan="2">
                        <table>
                            <th>Item</th>
                            <th>Value</th>
                            <th>Weight</th>
                            <th>Type</th>

                            <?php
                            $items = $character->getItems();

                            for ($i = 0; $i < 24; $i++) {
                                if ($i < sizeof($items)) {
                                    $item = $items[$i];
                                    ?>
                                    <tr>
                                        <td><input type="hidden" value="<?php echo $item->getId() ?>" name="itemId[]">
                                            <input type='text' value="<?php echo $item->getName() ?>" size=30 class='underscore' <?php $sheet->readOnly() ?> name="itemName[]"></td>
                                        <td><input type='text' value="<?php echo $item->getValue() ?>" size=1 class='underscore' <?php $sheet->readOnly() ?> name="itemValue[]"></td>
                                        <td><input type='text' value="<?php echo $item->getWeight() ?>" size=1 class='underscore' <?php $sheet->readOnly() ?> name="itemWeight[]"></td>
                                        <td><input type='text' value="<?php echo $item->getType() ?>" size=4 class='underscore' <?php $sheet->readOnly() ?> name="itemType[]"></td>
                                    </tr>

                                    <?php
                                } else {
                                    ?>
                                    <tr>
                                        <td><input type="hidden" name="itemId[]">
                                            <input type='text' size=30 class='underscore' <?php $sheet->readOnly() ?> name="itemName[]"></td>
                                        <td><input type='text' size=1 class='underscore' <?php $sheet->readOnly() ?> name="itemValue[]"></td>
                                        <td><input type='text' size=1 class='underscore' <?php $sheet->readOnly() ?> name="itemWeight[]"></td>
                                        <td><input type='text' size=4 class='underscore' <?php $sheet->readOnly() ?> name="itemType[]"></td>
                                    </tr>
                                    <?php
                                }
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
                    <td colspan="2">
                        <table>
                            <th>Notes</th>
                            <tr>
                                <td><textarea cols="60" rows="25" name="notesField" <?php $sheet->readOnly() ?> ><?php echo $character->getStat('CharNotes') ?></textarea></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <input type="submit" name="saveForm" value="Save">
            <input type="hidden" name="charID" value="<?php echo $character->getStat('Char_id') ?>">
        </form>
    </body>
</html>
