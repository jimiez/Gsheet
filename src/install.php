<html>
    <head>
        <title>
            Gsheet installation
        </title>
        <SCRIPT src="js/install.js"></SCRIPT>
        <link rel="stylesheet" type="text/css" href="style/style.css">
    </head>

    <?php
// Ajetaan jos pääkäyttäjän luontia kutsutaan
    if (isset($_POST['createAdmin'])) {
        getAdminDetails();
    }

// Ajetaan kun käyttäjä syöttää admin - tilin tunnukset
    if (isset($_POST['adminUser'])) {
        writeAdmin($_POST['adminUser'], $_POST['adminPass'], $_POST['adminPassConfirm']);
    }


// Jos on tarve resetoida tietokantatunnukset
    if (isset($_POST['resetCreds'])) {
        unlink("credentials");
    }


// Kutsutaan kun halutaan tallentaa tietokantatunnukset
    if (isset($_POST['submitCreds'])) {
        writeCredentials($_POST['dbAddress'], $_POST['dbName'], $_POST['user'], $_POST['pass']);
    }

// Luo kirjautumisruudun
    function getLogin() {
        ?>
        <form method='post' action="<?php $_SERVER['PHP_SELF'] ?>">

            <table>
                <tr><td>Address of the database:</td><td><Input type='text' name='dbAddress' value="localhost" size='15'></td></tr>
                <tr><td>Name of the database:</td><td><Input type='text' name='dbName' size='15'></td></tr>
                <tr><td>Username:</td><td><Input type='text' name='user' size='15'></td></tr>
                <tr><td>Password:</td><td><Input type='password' name='pass' size='15'></td></tr>
                <tr><td><input type='submit' name='submitCreds'></td></tr>
            </table>

        </form>
        <?php
    }

// Tarkistaa tietokantayhteyden ja sen onko admin-tiliä olemassa
    function checkDatabase() {
        if (file_exists("credentials.php")) {
            include ('connect.php');
            try {

                $query = $db->prepare("SELECT COUNT(*) as n FROM Users WHERE Userclass='1'");
                $query->execute();
                $result = $query->fetchObject();
                echo "Database connection successful!<br>";
                if ($result->n < 1) {
                    echo "No admin account found! Please create one now. <br><hr>";
                    getAdminDetails();
                }
            } catch (PDOException $e) {
                echo "No database detected! Trying to create a new database...<br>";
                createDB();
            }
        } else {
            if (!isset($_POST['submitCreds'])) {
                getLogin();
            }
        }
    }

// Admin-tilin luonti-ikkuna
    function getAdminDetails() {
        ?>
        <form method='post' action='<?php $_SERVER['PHP_SELF'] ?>' name='adminCreation'>
            <table>
                <tr><td>Username</td><td><input type='text' name='adminUser'></td></tr>
                <tr><td>Password</td><td><input type='password' name='adminPass'></td></tr>
                <tr><td>Re-type password</td><td><input type='password' name='adminPassConfirm'></td></tr>
                <tr><td><input type='button' value='Submit' name='adminSend' onClick='checkPasswords()'></td></tr>
            </table>
        </form>
        <?php
        die();
    }

// Kirjoittaa kantaan pääkäyttäjän tunnukset
    function writeAdmin($adminUser, $adminPass, $adminPassConfirm) {

        include ('connect.php');

        if ($adminPass != $adminPassConfirm) {
            echo "Password fields didn't match!";
        } else {
            $hashPass = md5($adminPass);
            $myquery = "INSERT INTO Users (username, password, userclass) VALUES ('$adminUser', '$hashPass', '1')";
            $query = $db->prepare($myquery);
            $query->execute();
            createDefaultCampaign($adminUser); // Luo oletuskamppiksen
            echo "Admin account created with the username $adminUser. <br>";
            echo "Gsheet is now ready for use!<br>";
            echo "<a href='login.php'>Log in to begin!</a>";
            die();
        }
    }

// Kirjoittaa tiedostoon tietokantatunnukset
    function writeCredentials($databaseAddress, $database, $username, $password) {

        try {
            $dabase = new PDO("mysql:host=$databaseAddress;dbname=$database", $username, $password);
            $dabase->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Incorrect login details or database name!";
            die();
        }

        $File = "credentials.php";
        $fh = fopen($File, 'w') or die("Can't write credentials! Check your folder privileges (should be 755)");

        $stringData = '<?php
            $dbaddress = "' . $databaseAddress . '";' . '
            $database = "' . $database . '";' . '
            $user = "' . $username . '";'. '
            $pass = "'. $password .'";'. '
?>';
        fwrite($fh, $stringData);
        fclose($fh);
    }

    // Tietokannan luonnin pääfunktio
    function createDB() {
        echo "Creating tables...";
        createTables();
        echo "done<br>";
        echo "Reading advantages and disadvantages...";
        insertAdvantages();
        echo "done<br>";
        echo "Reading skills...";
        insertSkils();
        echo "done<br>";
        echo "Database successfully created! Please create yourself an admin account now.<br>";
        echo "<form method='post'><input type='submit' name='createAdmin' value='Create admin account'></form>";
        die();
    }

    function createTables() {

        include ('connect.php');

        $myquery = file_get_contents("sql/tables.sql");
        $query = $db->prepare($myquery);
        $query->execute();
    }

    // Hakee attribuutteja xml - tiedostosta ja kirjoittaa ne kantaan
    function insertAdvantages() {

        include ('connect.php');
        for ($i = 0; $i < 2; $i++) {

            if ($i == 0) {
                $attributes = simplexml_load_file("xml/advantages.xml");
                $type = 'A';
            } else
            if ($i == 1) {
                $attributes = simplexml_load_file("xml/disadvantages.xml");
                $type = 'D';
            }

            try {
                $db->beginTransaction();

                foreach ($attributes as $attr) {
                    $parsedName = addslashes($attr->name);
                    $parsedDesc = addslashes($attr->description);
                    $parsedPoints = addslashes($attr->points);
                    $myquery = "INSERT INTO Attributes (
        AttrName ,
        AttrType ,
        AttrPoints ,
        AttrDesc) VALUES (
        '$parsedName', '$type',  '$parsedPoints',  '$parsedDesc');";

                    $query = $db->prepare($myquery);
                    $query->execute();
                }

                $db->commit(); // muutosten hyväksyntä
            } catch (PDOException $e) {
                $db->rollBack(); // muutosten peruutus
                die("ERROR: " . $e->getMessage());
            }
        }
    }

    // Hakee taitoja xml-tiedostosta ja kirjoittaa ne kantaan
    function insertSkils() {
        include ('connect.php');

        $skills = simplexml_load_file("xml/skills.xml");

        try {
            $db->beginTransaction();

            foreach ($skills as $skill) {
                $parsedName = addslashes($skill->name);
                $parsedType = addslashes($skill->type);
                $parsedDifficulty = addslashes($skill->difficulty);
                $parsedDefault = addslashes($skill->default);
                $parsedDesc = addslashes($skill->description);

                $myquery = $db->prepare("INSERT INTO Skills VALUES ('$parsedName', '$parsedType', '$parsedDifficulty', '$parsedDefault', '$parsedDesc')");

                $myquery->execute();
            }

            $db->commit(); // muutosten hyväksyntä
        } catch (PDOException $e) {
            $db->rollBack(); // muutosten peruutus
            die("ERROR: " . $e->getMessage());
        }
    }

    // Luo oletuskamppanjan

    function createDefaultCampaign($adminName) {
        include('connect.php');

        $query = $db->prepare("INSERT INTO Campaigns (CampOwner, CampName, CampNotes, CampPoints)VALUES (?, 'Default campaign', 'This is the default campaign', '100')");
        $query->bindValue(1, $adminName);
        $query->execute();
    }
    ?>

    <body>
        <noscript>Gsheet uses JavaScript extensively. Please turn it on from your browser if you wish to use Gsheet.</noscript>

        <h1>Gsheet - installation</h1>
        <?php
        checkDatabase();
        ?>
    </body>
</html>