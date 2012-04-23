<?php
include('connect.php');
?>

<html>
    <head>
        <title><?php echo $_GET['type'] ?> selector</title>
        <link rel="stylesheet" type="text/css" href="style/style.css">
        <script src="js/jquery.min.js" type="text/javascript" charset="utf-8"></script>
        <script src="js/selector.js" type="text/javascript" charset="utf-8"></script>
    </head>

    <body>
        <div id="wrap">
            <div id="leftFloat">
                <form name="selector">
                    <select id="selected" name="selected" size="20">
                        <?php
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
                        ?>
                    </select>
            </div>
            <div id="leftFloat">
                <b><div id="name"></div></b><br>
                <div id="points"></div><br>
                <textarea id="desc" cols="40" rows="15" readonly="readonly"></textarea>
                <br>
                <br>

                <input type="button" onClick="setValue(document.selector.selected.value)" value="Select" class="nicebutton">

            </div>
        </div>
    </body>
</html>