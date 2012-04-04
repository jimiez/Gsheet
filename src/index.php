<?php
include('session.php');

if ($_SESSION['isLogged'] != "true") {
    header("Location: login.php");
    Die();
}

include('connect.php');

if (isset($_POST['submitCamp'])) {
    $myquery = $db->prepare('INSERT INTO Campaigns (CampOwner, CampName, CampDesc) values (?, ?, ?)');
    $myquery->bindValue(1, $_SESSION['loggedUser']);
    $myquery->bindValue(2, $_POST['campName']);
    $myquery->bindValue(3, $_POST['campDesc']);
    $myquery->execute();
}

if (isset($_POST['hiddenDelete'])) {
    $myquery = $db->prepare('DELETE FROM Characters WHERE CharName = ?');
    $myquery->bindValue(1, $_POST['deletee']);
    $myquery->execute();
}
?>

<html>
    <head>
        <title>Gsheet - Characaters</title>
        <script src="js/index.js"></script>
        <link rel="stylesheet" type="text/css" href="style/style.css">
    </head>
    <body>
    <center>
        <div id="wrap">
            <div id="header"><div id="bigheader">Gsheet</div></div>
            <div id="nav">
                <ul>
                    <li><a href="index.php">Characters</a></li>
                    <li><a href="campaigns.php">Campaigns</a></li>
                    <li><a href="#">User settings</a></li>
                    <li><a href="#">Log out [<?php echo $_SESSION['loggedUser'] ?>]</a></li>
                </ul>
            </div>
            <div id="main">
                <h2>Characters</h2>
                <br>
                <b>View characters</b>
                <form name="charSelect" method="get" action="view.php">
                    <select name="id">
                        <?php
                        $myquery = $db->prepare('SELECT Char_id, CharName FROM Characters WHERE CharOwner= ?');
                        $myquery->bindValue(1, $_SESSION['loggedUser']);
                        $myquery->execute();
                        while ($result = $myquery->fetchObject()) {
                            echo "<option value='$result->Char_id'>$result->CharName</option>\n";
                        }
                        ?>
                    </select>
                    <input type="submit" class="nicebutton" value="View">
                </form>
                <br>
                <B>Create a new character</b>
                <form method="post" name="newChar" action="<?php $_SERVER['PHP_SELF'] ?>">

                    Name of the character
                    <input type="text" name="charName" size="20"><br>
                    <input type="submit" value="Create!" name="submitChar" class="nicebutton">
                </form>

                <B>Delete a character</b>

                <form name="charDelete" method="post" action="<?php $_SERVER['PHP_SELF'] ?>">
                    <select name="deletee">
                        <?php
                        $myquery = $db->prepare('SELECT Char_id, CharName FROM Characters WHERE CharOwner= ?');
                        $myquery->bindValue(1, $_SESSION['loggedUser']);
                        $myquery->execute();
                        while ($result = $myquery->fetchObject()) {
                            echo "<option value='$result->CharName'>$result->CharName</option>\n";
                        }
                        ?>
                    </select>
                    <input type="hidden" name="hiddenDelete">
                    <input type="button" name="charDeleteButton" value="Delete" class="nicebutton" onClick="confirmDelete()">
                </form>
            </div>
            <div id="footer">
                <div id="versioninfo"><p>Gsheet ver. 0.1</p></div>
                <div id="authorinfo"><p>JP Myllykangas, 2012</p></div>
            </div>
        </div>
    </center>
</body>

</html>