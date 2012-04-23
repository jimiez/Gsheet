<?php
include('connect.php')
?>

<html>
    <head>
        <link rel="stylesheet" type="text/css" href="style/style.css">

        <script src="js/selector.js" type="text/javascript" charset="utf-8"></script>
        <script type="text/javascript">
            function showDetails(str) {
          
                xmlhttp=new XMLHttpRequest();
            
                xmlhttp.onreadystatechange = function() {
                    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                        document.getElementById("details").innerHTML = xmlhttp.responseText;
                    }
                }
                xmlhttp.open("GET", "moe.php?q=" + str, true);
                xmlhttp.send();
            }
        </script>
    </head>
    <body>
        <div id="wrap">
            <div id="leftFloat">
                <form name="selector">
                    <select id="selected" name="selected" size="20" onChange="showDetails(this.value)">
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
                <div id="details"></div>
            </div>
    </body>
</html>