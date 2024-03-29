<?php

if (isset($_POST['submitChar'])) {
    $myquery = $db->prepare('INSERT INTO Characters (CharName, CharOwner) values (?, ?)');
    $myquery->bindValue(1, $_POST['charName']);
    $myquery->bindValue(2, $_SESSION['loggedUser']);
    $myquery->execute();
}

if (isset($_POST['hiddenDelete'])) {
    $myquery = $db->prepare('DELETE FROM Characters WHERE CharName = ?');
    $myquery->bindValue(1, $_POST['deletee']);
    $myquery->execute();
}
?>
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

<hr>

<B>Create a new character</b>
<form method="post" name="newChar" action="<?php $_SERVER['PHP_SELF'] ?>">

    Name of the character
    <input type="text" name="charName" size="20"><br>
    <input type="submit" value="Create!" name="submitChar" class="nicebutton">
</form>

<hr>

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