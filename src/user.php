<?php
if (isset($_POST['oldPass'])) {

    if (checkPass($_POST['oldPass'])) {

    $myquery = $db->prepare('UPDATE Users SET 
    Password = ?
    WHERE Username = ?');

    $myquery->bindValue(1, md5($_POST['newPass']));
    $myquery->bindValue(2, $_SESSION['loggedUser']);
    $myquery->execute();
    echo "<br><br><b>Password succesfully changed</b>";
    die();
    } else {
        echo "<br><br><b>Old password was wrong!</b>";
        die();
    }
}

function checkPass($password) {

    include('connect.php');

    $myquery = $db->prepare('SELECT Password FROM Users WHERE Username = ?');

    $myquery->bindValue(1, $_SESSION['loggedUser']);

    $myquery->execute();

    $result = $myquery->fetchObject();
    
    if ($result->Password == md5($password)) {
        return true;
    } else {
        return false;
    }
}
?>

<h2>User settings</h2>

<b>Change password</b>

<p>Current password:</p>

<form name="changePass" method="post" action="<?php $_SERVER['PHP_SELF'] ?>">

    <input type="password" name="oldPass" size="10">

    <p>New password:</p>

    <input type="password" name="newPass" size="10">

    <p>Re-type new password:</p>

    <input type="password" name="newPassConfirm" size="10">

    <br>

    <input name="submitPass" type="button" onClick="checkPasswords()" value="Change">

</form>