<?php
include 'classes.php';

$sheet = new Sheet();
?>

<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" type="text/css" href="style/style.css">
        <SCRIPT src="js/view.js"></SCRIPT>
    </head>
    <body>
        <form name="baseform" onClick="updateAll()">
            <table>
                <tr><td>Name:</td><td><input name="nameField" type="text" class="underscore" size="35" <?php
$sheet->readOnly();
$sheet->getValue('name');
?> ></td></tr>
                <tr><td>Appearance:</td><td><input name="appearanceField" type="text" class="underscore" size="35" <?php
                                             $sheet->readOnly();
                                             $sheet->getValue('appearance')
?>></td></tr>
                <tr><td>Description:</td><td><input name="storyField" type="text" class="underscore" size="35" <?php
                                                   $sheet->readOnly();
                                                   $sheet->getValue('description')
?>></td></tr>
            </table>

            <table>
                <tr><td><h2>ST</h2></td><td><input name="stField" type="text" class="boxy" size="2" <?php $sheet->getValue('st') ?> readonly="readonly"></td>
                    <?php
                    $sheet->drawButtons("st");
                    ?>
                </tr>
                <tr><td><h2>DX</h2></td><td><input name="dxField" type="text" class="boxy" size="2" <?php $sheet->getValue('dx') ?> readonly="readonly"></td>
                    <?php
                    $sheet->drawButtons("dx");
                    ?>
                </tr>
                <tr><td><h2>IQ</h2> </td><td><input name="iqField" type="text" class="boxy" size="2" <?php $sheet->getValue('iq') ?> readonly="readonly"></td>
                    <?php
                    $sheet->drawButtons("iq");
                    ?>
                </tr>
                <tr><td><h2>HT</h2> </td><td><input name="htField" type="text" class="boxy" size="2" <?php $sheet->getValue('ht') ?>  readonly="readonly"></td>
                    <?php
                    $sheet->drawButtons("ht");
                    ?>
                </tr>
            </table>

            <table>
                <tr>
                    <td colspan="2"><b>Basic damage:</b></td>
                <tr>
                    <td>Thrust:</td><td><input type="text" size=5 name="dmgThrustField" readonly="readonly"></td>
                </tr>
                <tr>
                    <td>Slash:</td><td><input type="text" size=5 name="dmgSlashField" readonly="readonly"></td>
                </tr>
            </table>

            <table>
                <tr>
                    <td>Hits taken:</td>
                    <td><input type="text" size=2 name="hitsTakenField" <?php $sheet->getValue('hitsTaken') ?> readonly="readonly">
                        <?php
                        $sheet->drawButtons("hitsTaken");
                        ?>
                    </td></tr>
                <tr><td>Fatigue:</td><td><input type="text" name="fatigueField" <?php $sheet->getValue('fatigue') ?> size="2" readonly="readonly">
                        <?php
                        $sheet->drawButtons("fatigue");
                        ?>
                    </td>
                </tr>
            </table>

            <table>
                <tr>
                    <td>Basic speed:</td><td><input type="text" name="basicSpeedField" size="2" readonly="readonly"></td>
                </tr>
                <tr>
                    <td>Move:</td><td><input type="text" name="moveField" size="2" readonly="readonly"></td>
                </tr>
                <tr>
                    <td>Encumbrance:</td><td><input type="text" name="encumbranceField" readonly="readonly" size="5"></td>
                </tr>
            </table>

            <table>
                <tr><td colspan="2"><b>Disadvantages</b></td></tr>

            </table>

            <table>
                <tr>
                    <td colspan="2"><b>Advantages</td>
                </tr>
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

                <tr><td style="border: solid 0 #060; border-top-width:2px; padding-left:0.5ex"><i>Total</i></td>
                    <td><input type="text" readonly="readonly" value="<?php echo $totalAdvantage ?>" class="borderless" size="1"></td></tr>
            </table>


        </form>
    </body>
</html>
