<?php

     if (file_exists("credentials")) {
             $crds = parse_ini_file("credentials");
     } else {
         echo "Fatal error, credentials missing. Please run install.php";
         die();
     }
    $database = $crds['db'];
    $user = $crds['user'];
    $pass = $crds['pass'];


    $db = new PDO("mysql:host=localhost;dbname=$database", $user, $pass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
?>
