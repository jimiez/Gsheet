<?php
include 'classes.php';

function drawItemSelection($selected) {

    echo "<select name='itemType'>";

    echo "<option></option>";
    if ($selected == "weapon") {
        echo "<option selected=selected>Weapon</option>";
    } else {
        echo "<option>Weapon</option>";
    }
    echo "<option>Protection</option>";
    echo "<option>Misc</option>";
    echo "</select>";
}

$sheet = new Sheet();
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Gsheet - <?php echo $sheet->getValuePure('name') ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" type="text/css" href="style/style.css">
        <SCRIPT src="js/view.js"></SCRIPT>
    </head>

    <body onLoad="updateAll()">

        <form name="baseform" onClick="updateAll()" action="save.php">

            <table>
                <th>
                    Basic information
                </th>
                <tr>
                    <td>
                        Name
                    </td>
                    <td>
                        <input name="nameField" type="text" class="underscore" size="35" 
                        <?php
                        $sheet->readOnly();
                        $sheet->getValue('name');
                        ?>>
                    </td>
                </tr>
                <tr>
                    <td>
                        Appearance
                    </td>
                    <td>
                        <input name="appearanceField" type="text" class="underscore" size="35" 
                        <?php
                        $sheet->readOnly();
                        $sheet->getValue('appearance')
                        ?>>
                    </td></tr>
                <tr>
                    <td>
                        Description
                    </td>
                    <td>
                        <input type="text" name="storyField" size='35' class="underscore" 
                        <?php
                        $sheet->readOnly();
                        $sheet->getValue('description')
                        ?>>
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
                        <input name="stField" type="text" class="boxy" size="2" <?php $sheet->getValue('st') ?> readonly="readonly">
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
                        <input name="dxField" type="text" class="boxy" size="2" <?php $sheet->getValue('dx') ?> readonly="readonly">
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
                        <input name="iqField" type="text" class="boxy" size="2" <?php $sheet->getValue('iq') ?> readonly="readonly">
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
                        <input name="htField" type="text" class="boxy" size="2" <?php $sheet->getValue('ht') ?>  readonly="readonly">
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
                        <input type="text" size=2 name="hitsTakenField" <?php $sheet->getValue('hitsTaken') ?> readonly="readonly">
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
                        <input type="text" name="fatigueField" <?php $sheet->getValue('fatigue') ?> size="2" readonly="readonly">
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

                <?php
                $totalAdvantage = 0;

                foreach ($sheet->getAttributes("adv") as $advantage) {
                    echo "<tr><td>";
                    echo $advantage->getName();
                    echo "</td><td>";
                    echo $advantage->getPoints();
                    echo "</td></tr>";
                    $totalAdvantage += $advantage->getPoints();
                }
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

                foreach ($sheet->getAttributes("dis") as $disadvantage) {
                    echo "<tr><td>";
                    echo $disadvantage->getName();
                    echo "</td><td>";
                    echo $disadvantage->getPoints();
                    echo "</td></tr>";
                    $totalDisadvantage += $disadvantage->getPoints();
                }
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
                }
                ?>
            </table> 


        </form>
    </body>
</html>
