<?php

session_start();

// Expires the session after 30 minutes

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
    // last request was more than 30 minates ago (1800)
    session_destroy();   // destroy session data in storage
    session_unset();     // unset $_SESSION variable for the runtime
}
$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp
// Creates a new session ID every 10 minutes to protect against session hijacking

if (!isset($_SESSION['CREATED'])) {
    $_SESSION['CREATED'] = time();
} else if (time() - $_SESSION['CREATED'] > 600) {
    // session started more than 10 minates ago
    session_regenerate_id(true);    // change session ID for the current session and invalidate old session ID
    $_SESSION['CREATED'] = time();  // update creation time
}

function logOut() {
    session_destroy(); 
    session_unset();
    header("Location: login.php");
}

?>
