<?php
include('connect.php');

/*
 * Sisältää kaikki 'ajax' - tyyliset pyynnöt palvemille
 */

// Get-muuttuja määrittää mitä kannasta haetaan

if (isset($_GET['attr'])) {
    $myquery = $db->prepare('SELECT * FROM Attributes WHERE AttrName = ?');
    $myquery->bindValue(1, $_GET['attr']);
    $myquery->execute();
    $result = $myquery->fetchObject();

    echo "<b>$result->AttrName</b><br><br>
    Point cost: $result->AttrPoints<br><br>
    <textarea cols='40' rows='15' readonly='readonly'>$result->AttrDesc</textarea><br><br>
    <input type='button' onClick=\"setAttrValue('$result->AttrName', '$result->AttrPoints', '$result->AttrType')\" value='Select' class='nicebutton'>";
}

if (isset($_GET['skill'])) {
    $myquery = $db->prepare('SELECT * FROM Skills WHERE SkillName = ?');
    $myquery->bindValue(1, $_GET['skill']);
    $myquery->execute();
    $result = $myquery->fetchObject();

    if ($result->SkillType == 'M') {
        $type = 'Mental';
    } else {
        $type = 'Physical';
    }

    echo "<b>$result->SkillName</b><br><br>
    $type / $result->SkillDiff<br>
    Defaults to:  $result->SkillDefault<br><br>
    <textarea cols='40' rows='14' readonly='readonly'>$result->SkillDesc</textarea><br><br>
    <input type='button' onClick=\"setSkillValue('$result->SkillName', '$result->SkillType', '$result->SkillDiff')\" value='Select' class='nicebutton'>";
}


// Kun halutaan muokata jotain attribuuttia
if (isset($_GET['editAttr'])) {

    $myquery = $db->prepare('SELECT * FROM Attributes WHERE AttrName = ?');
    $myquery->bindValue(1, $_GET['editAttr']);
    $myquery->execute();
    $result = $myquery->fetchObject();

    $attrName = $result->AttrName;
    $attrType = $result->AttrType;
    $attrPoints = $result->AttrPoints;
    $attrDesc = $result->AttrDesc;
    ?>
    <form name="ChangeAttribute" method="post" action="<?php $_SERVER['PHP_SELF'] ?>">
        Name: <input type='text' name='attrName' value='<?php echo $attrName ?>'><br><br>
        Point cost: <input type='text' name='attrPoints' value='<?php echo $attrPoints ?>'><br><br>
        <textarea cols='40' rows='15' name='attrDesc'><?php echo $attrDesc ?></textarea><br><br>
        <input type='hidden' name='attrType' value='<?php echo $attrType ?>'>
        <input type='hidden' name='originalAttrName' value='<?php echo $attrName ?>'>
        <input type='submit' name='saveAttribute' value='Save changes' class='nicebutton'>
    </form>
    <?php
}


// Kun halutaan muokata jotain skilliä
if (isset($_GET['editSkill'])) {

    $myquery = $db->prepare('SELECT * FROM Skills WHERE SkillName = ?');
    $myquery->bindValue(1, $_GET['editSkill']);
    $myquery->execute();
    $result = $myquery->fetchObject();

    $skillName = $result->SkillName;
    $skillType = $result->SkillType;
    $skillDiff = $result->SkillDiff;
    $skillDefault = $result->SkillDefault;
    $skillDesc = $result->SkillDesc;
    ?>
    <form name="ChangeAttribute" method="post" action="<?php $_SERVER['PHP_SELF'] ?>">
        Name: <input type='text' name='skillName' value='<?php echo $skillName ?>'><br>
        Type: <select name="skillType">
            <option value="P" <?php if ($skillType == "P") echo "selected='yes'" ?>>Physical</option>
            <option value="M" <?php if ($skillType == "M") echo "selected='yes'" ?>>Mental</option>
        </select><br>
        Difficulty: <select name="skillDiff">
            <option value="Easy" <?php if ($skillDiff == "Easy") echo "selected='yes'" ?>>Easy</option>
            <option value="Average" <?php if ($skillDiff == "Average") echo "selected='yes'" ?>>Average</option>
            <option value="Hard" <?php if ($skillDiff == "Hard") echo "selected='yes'" ?>>Hard</option>
            <option value="Very Hard" <?php if ($skillDiff == "Very Hard") echo "selected='yes'" ?>>Very Hard</option>
        </select><br>
        Defaults to: <input type='text' name='skillDefault' size="30" value='<?php echo $skillDefault ?>'><br><br>
        <textarea cols='40' rows='12' name='skillDesc'><?php echo $skillDesc ?></textarea><br><br>

        <input type='hidden' name='originalSkillName' value='<?php echo $skillName ?>'>
        <input type='submit' name='saveSkill' value='Save changes' class='nicebutton'>
    </form>
    <?php
}

// Lomakkeen tulostukseen

if (isset($_GET['form'])) {

    if ($_GET['form'] == 'attribute') {
        ?>

        <form name="newAttribute" method="post" action="<?php $_SERVER['PHP_SELF'] ?>">
            <table>
                <tr><td>Name</td><td><input type="text" name="attrName" size="30"></td></tr>
                <tr><td>Type</td><td>
                        <select name="attrType">
                            <option value="A">Advantage</option>
                            <option value="D">Disadvantage</option>
                        </select></td></tr>
                <tr><td>Points:</td><td><input type="text" name="attrPoints" size="2"></td></tr>
                <tr><td colspan="2"><textarea name="attrDesc" rows="12" cols="30">Description</textarea></td></tr>
                <tr><td><input type="button" name="saveAttr" value="Save" onClick="checkAttrInput()"></td></tr>
            </table>

        </form>

        <?php
    } else if ($_GET['form'] == 'skill') {
        ?>

        <form name="newSkill" method="post" action="<?php $_SERVER['PHP_SELF'] ?>">
            <table>
                <tr><td>Name</td><td><input type="text" name="skillName" size="30"></td></tr>
                <tr><td>Type</td><td>
                        <select name="skillType">
                            <option value="P">Physical</option>
                            <option value="M">Mental</option>
                        </select></td></tr>
                <tr><td>Difficulty</td><td>
                        <select name="skillDiff">
                            <option value="Easy">Easy</option>
                            <option value="Average">Average</option>
                            <option value="Hard">Hard</option>
                            <option value="Very Hard">Very Hard</option>
                        </select></td></tr>
                <tr><td>Defaults to:</td><td><input type="text" name="skillDefault" size="30"></td></tr>
                <tr><td colspan="2"><textarea name="skillDesc" rows="12" cols="35">Description</textarea></td></tr>
                <tr><td><input type="button" name="saveSkill" value="Save" onClick="checkSkillInput()"></td></tr>
            </table>
        </form>
        <?php
    } else {
        
    }
}
?>


