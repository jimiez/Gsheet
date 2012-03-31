<?php
include('session.php');

if ($_SESSION['isLogged'] != "true") {
    header("Location: login.php");
    Die();
}

if (isset($_POST['logOut'])) {
    logOut();
}

include('connect.php');

if (isset($_POST['submitChar'])) {
    $myquery = $db->prepare('INSERT INTO Characters (CharName, CharOwner) values (?, ?)');
    $myquery->bindValue(1, $_POST['charName']);
    $myquery->bindValue(2, $_SESSION['loggedUser']);
    $myquery->execute();
}

?>

<html>
    <head>
        <title>Gsheet - main page</title>
        <script src="index.js"></script>
        <link rel="stylesheet" type="text/css" href="style/style.css">
    </head>
    <body>
        <h1>Gsheet</h1>
        <p>Welcome to Gsheet!</p>
        <br>
        <B>Create a new character</b>
        <form method="post" name="newChar" action="<?php $_SERVER['PHP_SELF'] ?>">
            
            <p>Name of the character</p>
            <input type="text" name="charName" size="20"><br>
            <input type="submit" value="Create!" name="submitChar" class="nicebutton">
        </form>


        <form method='post' name='logout' action=<?php $_SERVER['PHP_SELF'] ?>>
            <input type="submit" name="logOut" value="Log out" class=nicebutton>
        </form>
    </body>

</html>