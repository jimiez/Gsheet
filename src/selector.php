<?php
include('connect.php');

// Tämä sivu kutsutaan kun halutaan valita attribuutteja tai taitoja
// Tallentaa muutokset attribuuteissa
if (isset($_POST['saveAttribute'])) {
    $myquery = $db->prepare('UPDATE Attributes SET
        AttrName = ?,
        AttrType = ?,
        AttrPoints = ?,
        AttrDesc = ?
        WHERE AttrName = ?');
    $myquery->bindValue(1, $_POST['attrName']);
    $myquery->bindValue(2, $_POST['attrType']);
    $myquery->bindValue(3, $_POST['attrPoints']);
    $myquery->bindValue(4, $_POST['attrDesc']);
    $myquery->bindValue(5, $_POST['originalAttrName']);

    $myquery->execute();
}

if (isset($_POST['saveSkill'])) {
    $myquery = $db->prepare('UPDATE Skills SET
        SkillName = ?,
        SkillType = ?,
        SkillDiff = ?,
        SkillDefault = ?,
        SkillDesc = ?
        WHERE SkillName = ?');
    $myquery->bindValue(1, $_POST['skillName']);
    $myquery->bindValue(2, $_POST['skillType']);
    $myquery->bindValue(3, $_POST['skillDiff']);
    $myquery->bindValue(4, $_POST['skillDefault']);
    $myquery->bindValue(5, $_POST['skillDesc']);
    $myquery->bindValue(6, $_POST['originalSkillName']);

    $myquery->execute();
}
?>

<html>
    <head>
        <title><?php echo $_GET['type'] ?> selector</title>
        <link rel="stylesheet" type="text/css" href="style/style.css">
        <script src="js/selector.js" type="text/javascript" charset="utf-8"></script>
    </head>
    <body>
        <div id="wrap">
            <div id="leftFloat">
                <form name="selector">

<?php
if ($_GET['type'] == 'skill') {
    echo "<select id='selected' name='selected' size='20' onChange=\"showDetails(this.value, 'skill')\">";
    $myquery = $db->prepare("SELECT SkillName FROM Skills");
    $myquery->execute();
    while ($result = $myquery->fetchObject()) {
        echo "<option id='$result->SkillName'>$result->SkillName</option>";
    }
} else {
    echo "<select id='selected' name='selected' size='20' onChange=\"showDetails(this.value, 'attr')\">";
    if ($_GET['type'] == 'advantage') {
        $type = 'A';
    } else {
        $type = 'D';
    }
    $myquery = $db->prepare("SELECT AttrName FROM Attributes WHERE AttrType = '$type'");
    $myquery->execute();
    while ($result = $myquery->fetchObject()) {
        echo "<option id='$result->AttrName'>$result->AttrName</option>";
    }
}
?>

                    </select>
                    <br>
<?php
if ($_GET['type'] == 'skill') {
    ?>
                        <input type="button" class="minibutton" name="edit" value="Edit" onClick="showDetails(document.selector.selected.value, 'editSkill')">
                        <?php
                    } else {
                        ?>
                        <input type="button" class="minibutton" name="edit" value="Edit" onClick="showDetails(document.selector.selected.value, 'editAttr')">
                        <?php
                    }
                    ?>
                    </div>
                    <div id="leftFloat">
                        <div id="details"></div>
                    </div>
            </div>
    </body>
</html>