<?php
session_start();

$message = "Please log in";
$fail = "<b><font color=#ff0000>Wrong username or password!</font></b>";
include('connect.php');

if (isset($_POST['submitLogin'])) {
    $user = $_POST['userName'];
    
    $myquery = $db->prepare('SELECT COUNT(*) AS n FROM Users WHERE Username=?');
    $myquery->bindValue(1, $user);
    $myquery->execute();

    $result = $myquery->fetchObject();

    if ($result->n < 1) {
        $message = $fail;
    } else {
        $myquery = $db->prepare('SELECT password FROM Users WHERE Username=?');
        $myquery->bindValue(1, $_POST['userName']);
        $myquery->execute();
        $result = $myquery->fetchObject();
        $password = md5($_POST['password']);
        if ($result->password == $password) {
            $message = "Login successful";
            $_SESSION['isLogged'] = "true";
            $_SESSION['loggedUser'] = $user;
            header("Location: index.php");
   
        } else {
            $message = $fail;
        }
    }
}

?>

<html>
    <head>
        <title>Gsheet - login</title>
        <link rel="stylesheet" type="text/css" href="style/style.css">
    </head>
    <body>
        <h1>
            Gsheet
        </h1>
        <?php
        echo "$message <br><br>";
        ?>

        <form name="login" method="post" action="">
            <table>
                <tr>
                    <td>
                        User:
                    </td>
                    <td>
                        <input type="text" size="15" name="userName">
                    </td>
                </tr>
                <tr>
                    <td>
                        Password:
                    </td>
                    <td>
                        <input type="password" size="15" name="password">
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="submit" name="submitLogin" class=nicebutton>
                    </td>
                </tr>
            </table>
        </form>


    </body>
</html>
