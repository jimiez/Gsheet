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

if (isset($_POST['submitCamp'])) {
    $myquery = $db->prepare('INSERT INTO Campaigns (CampOwner, CampName, CampDesc) values (?, ?, ?)');
    $myquery->bindValue(1, $_SESSION['loggedUser']);
    $myquery->bindValue(2, $_POST['campName']);
    $myquery->bindValue(3, $_POST['campDesc']);
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
        Select a charcter:
        <form name="charSelect" method="get" action="view.php">
            <select name="id">
                <?php
                $myquery = $db->prepare('SELECT Char_id, CharName FROM Characters WHERE CharOwner= ?');
                $myquery->bindValue(1, $_SESSION['loggedUser']);
                $myquery->execute();
                while ($result = $myquery->fetchObject()) {
                    echo "<option value='$result->Char_id'>$result->CharName</option>";
                }
                ?>
            </select>
            <input type="submit" class="nicebutton">
        </form>
        <B>Create a new character</b>
        <form method="post" name="newChar" action="<?php $_SERVER['PHP_SELF'] ?>">

            <p>Name of the character</p>
            <input type="text" name="charName" size="20"><br>
            <input type="submit" value="Create!" name="submitChar" class="nicebutton">
        </form>
        <br>
        <b>Create a new campaign</b>
        <br>
        <br>

        <form name="newCampaign" method="post" action="<?php $_SERVER['PHP_SELF'] ?>">
            <p>Name of the campaign</p>
            <input type="text" name="campName" size="20"><br>
            <p>Description of the campaign</p>
            <textarea name="campDesc" rows="5" cols="20">Description of the campaign here</textarea><br>
            <input type="submit" value="Create!" name="submitCamp" class="nicebutton">
        </form>

        <br><br>
        <form method='post' name='logout' action=<?php $_SERVER['PHP_SELF'] ?>>
            <input type="submit" name="logOut" value="Log out" class=nicebutton>
        </form>

    </body>

</html>