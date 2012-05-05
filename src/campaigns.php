<?php

if (isset($_POST['submitCamp'])) {
    $myquery = $db->prepare('INSERT INTO Campaigns (CampOwner, CampName, CampNotes, CampPoints) values (?, ?, ?, ?)');
    $myquery->bindValue(1, $_SESSION['loggedUser']);
    $myquery->bindValue(2, $_POST['campName']);
    $myquery->bindValue(3, $_POST['campDesc']);
    $myquery->bindValue(4, $_POST['campPoints']);
    $myquery->execute();
}
?>


<h2>My campaigns</h2>
<br>
<b>View campaigns</b>
<br>
<form name="campaignView" method="GET" action="campaignView.php" target="Campaign" onSubmit="window.open('Campaign', 'Campaign', 'width=800,height=600,status=yes,resizable=yes,scrollbars=yes')">
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
    <p>Description of the campaign / Notes</p>
    <textarea name="campDesc" rows="5" cols="20">Description of the campaign here</textarea><br>
    <p>Character starting points</p>
    <input type="text" size="3" name="campPoints"><br>
    <input type="submit" value="Create!" name="submitCamp" class="nicebutton">
</form>
