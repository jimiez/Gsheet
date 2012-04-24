<?php
include('connect.php')
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
                        echo "<select id='selected' name='selected' size='20' onChange=\"showDetails(this.value, 'S')\">";
                        $myquery = $db->prepare("SELECT SkillName FROM Skills");
                        $myquery->execute();
                        while ($result = $myquery->fetchObject()) {
                            echo "<option id='$result->SkillName'>$result->SkillName</option>";
                        }
                    } else {
                        echo "<select id='selected' name='selected' size='20' onChange=\"showDetails(this.value, 'A')\">";
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
            </div>
            <div id="leftFloat">
                <div id="details"></div>
            </div>
        </div>
    </body>
</html>