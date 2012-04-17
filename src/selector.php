<?php
include('connect.php');

$_GET['type'] = "Advantage";
?>

<html>
    <head>
        <title><?php echo $_GET['type'] ?> Selector</title>
        <link rel="stylesheet" type="text/css" href="style/style.css"></link>
        <script src="js/jquery.min.js" type="text/javascript" charset="utf-8"></script>
        <script type="text/javascript" charset="utf-8">



            $(document).ready(function(){
                $('#selected').change(function(){
                    getQuery($(this).val());                   
                });
            
            });
            function getQuery(str){
                $.post("ajax.php",{ getQuery: str },
                function(data){
                    $('#name').html(data.attrName);
                    $('#points').html(data.attrPoints);
                    $('#desc').html(data.attrDesc);
                }, "json");      
                
            }
        
            function setValue(value){
                if (opener && !opener.closed && opener.setTargetField){
                    opener.setTargetField(targetField, value);
                }
                window.close();
            }
             
        </script>
    </head>

    <body>
        <div id="wrap">
            <div id="nav">
                <form name="selector">
                    <select id="selected" name="selected" size="20">
                        <?php
                        $myquery = $db->prepare('SELECT AttrName FROM Attributes');
                        $myquery->execute();
                        while ($result = $myquery->fetchObject()) {
                            echo "<option id='$result->AttrName'>$result->AttrName</option>";
                        }
                        ?>
                    </select>
            </div>
            <div id="main">
                <b><div id="name"></div></b><br>
                <p><div id="points"></div><br>
                <div id="desc"></div></p>
                Points:

                <br>
                <input type="button" onClick="setValue(document.selector.selected.value)" value="Select">
            </div>
        </div>
    </body>
</html>