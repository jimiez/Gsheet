<?php
if (isset($_SESSION['loggedUser'])) {
    if (!$_SESSION['userIsAdmin']) {
        echo "You don't have the necesary privlidges to view this page.";
        die();
    }
} else {
    echo "Must be logged in to view this page.";
    die();
}

if (isset($_POST['submitUser'])) {

    if (isset($_POST['isadmin'])) {
        $userclass = 1;
    } else {
        $userclass = 0;
    }

    $myquery = $db->prepare('INSERT INTO Users VALUES 
    (?, ?, ?)');

    $myquery->bindValue(1, $_POST['user']);
    $myquery->bindValue(2, md5($_POST['pass']));
    $myquery->bindValue(3, $userclass);
    $myquery->execute();
    echo "<br><br><b>New user, " . $_POST['user'] . ", created!</b>";
    die();
}

if (isset($_POST['changePass'])) {

    $myquery = $db->prepare('UPDATE Users SET 
    Password = ? WHERE Username = ?');

    $myquery->bindValue(1, md5($_POST['newPass']));
    $myquery->bindValue(2, $_POST['userList']);
    $myquery->execute();
    echo "<br><br><b>Password reset for " . $_POST['userList'];
    die();
}
?>

<h2>Admin options</h2>

<b>Create new user</b>

<form name="newUser" method="post" action="<?php $_SERVER['PHP_SELF'] ?>">

    <p>Username:</p>      

    <input type="text" name="user" size="10">

    <p>Password</p>

    <input type="text" name="pass" size="10">

    <br>

    User is admin: <input type="checkbox" name="isadmin">
    <br><br>

    <input name="submitUser" type="submit" value="Create">

    <hr>

    <b>Reset password</b>
    <br><br>
    <form name="resetPass" method="post" action="<?php $_SERVER['PHP_SELF'] ?>">
        <div id="leftFloat">
            <select name="userList" size="5">
                <?php
                $myquery = $db->prepare('SELECT Username FROM Users');
                $myquery->execute();

                while ($result = $myquery->fetchObject()) {
                    echo "<option value='" . $result->Username . "'>$result->Username</option>";
                }
                ?>

            </select>
        </div>
        <div id="leftFloat">
            New password:<br>
            <input type="text" name="newPass" size="15"><br><br>
            <input type="submit" value="Change" name="changePass">
        </div>        
    </form>

