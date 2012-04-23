<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>

<html>
    <head>
        <script>
            function setValue(value1, value2, value3){
                if (opener && !opener.closed && opener.setTargetField){
                    opener.setTargetField(targetField, value1, value2, value3);
                }
                window.close();
            }
        </script>
    </head>
    <body>
        <form name="testForm">
            <input type="text" name="value1">
            <input type="text" name="value2">
            <input type="text" name="value3">
            <input type="button" name="submitButton" onClick="setValue(document.testForm.value1.value, document.testForm.value2.value, document.testForm.value3.value)">
        </form>
    </body>
</html>