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

try {
    $db = new PDO("mysql:host=localhost;dbname=$database", $user, $pass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Database connection failed.<br>Please make sure you have the correct database name and/or credentials.";
    echo "<br><form action='install.php' method='post'><input type='submit' class='linkbutton' value='Insert new credentials' name='resetCreds'></form>";
    die();
}


?>
