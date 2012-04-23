<?php
include('session.php');



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
        <link rel="stylesheet" type="text/css" href="style/style.css">
        <script src="js/jquery.min.js" type="text/javascript" charset="utf-8"></script>

    </head>
    <body>
    <center>
        <div id="wrap">
            <div id="header"><div id="bigheader">Gsheet</div></div>
            <div id="nav">
                <?php
                include('nav.php');
                ?>
            </div>
            <div id="main">
                <h2>My campaigns</h2>
                <br>
                <b>View campaigns</b>
                <br>
                <form name="campaignView" method="GET" action="campaignView.php">
                    <select name="id">
                        <?php
                        $myquery = $db->prepare('SELECT Campaign_id, CampName FROM Campaigns WHERE CampOwner = ?');
                        $myquery->bindValue(1, $_SESSION['loggedUser']);
                        $myquery->execute();
                        while ($result = $myquery->fetchObject()) {
                            echo "<option value='$result->Campaign_id'>$result->CampName</option>";
                        }
                        ?>
                    </select>
                    <input type="submit" class="nicebutton" value="View">
                </form>
                <hr>
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