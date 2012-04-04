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
?>

<html>
    <head>
        <title>Gsheet - Campaigns</title>
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
                <h2>Campaigns</h2>



                <br>
                <b>Create a new campaign</b>
           

                <form name="newCampaign" method="post" action="<?php $_SERVER['PHP_SELF'] ?>">
                    <p>Name of the campaign</p>
                    <input type="text" name="campName" size="20"><br>
                    <p>Description of the campaign</p>
                    <textarea name="campDesc" rows="5" cols="20">Description of the campaign here</textarea><br>
                    <input type="submit" value="Create!" name="submitCamp" class="nicebutton">
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