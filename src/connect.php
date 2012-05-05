<?php

if (file_exists("credentials.php")) {
    include('credentials.php');
} else {
    echo "The credentials are missing! Please run <a href='install.php'>install.php</a>";
    die();
}
try {
    $db = new PDO("mysql:host=$dbaddress;dbname=$database", $user, $pass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Database error: $e";
}
?>
