<ul>
    <li><a href="index.php">My characters</a></li>
    <li><a href="campaigns.php">My campaigns</a></li>
    <li><a href="user.php">User settings</a></li>
    <?php
    if ($_SESSION['userIsAdmin'] == "true") {
        echo "<li><a href='admin.php'>Admin</a></li>";
    }
    ?>
    <li><a href="session.php?logout">Log out [<?php echo $_SESSION['loggedUser'] ?>]</a></li>
</ul>


