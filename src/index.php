<?php
include('session.php');
include('connect.php');

if (!isset($_GET['open'])) {
    $open = "characters";
} else {
    $open = $_GET['open'];
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
                    <li><a href="index.php?open=characters">My characters</a></li>
                    <li><a href="index.php?open=campaigns">My campaigns</a></li>
                    <li><a href="index.php?open=user">User settings</a></li>
                    <li><a href="index.php?open=editor">Editor</a></li>
                    <?php
                    if ($_SESSION['userIsAdmin'] == "true") {
                        echo "<li><a href='index.php?open=admin'>Admin</a></li>";
                    }
                    ?>
                    <li><a href="session.php?logout">Log out [<?php echo $_SESSION['loggedUser'] ?>]</a></li>
                </ul>

            </div>
            <div id="main">
                <?php
                include($open . ".php");
                ?>
            </div>
            <div id="footer">
                <div id="versioninfo"><p>Gsheet ver. 0.5</p></div>
                <div id="authorinfo"><p>JP Myllykangas, 2012</p></div>
            </div>
        </div>
    </center>
</body>

</html>