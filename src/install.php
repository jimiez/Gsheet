<html>

    <head>
        <title>
            Gsheet installation
        </title>
        <SCRIPT src="js/install.js"></SCRIPT>
    </head>
    <body>
        <noscript>Gsheet uses JavaScript extensively. Please turn it on from your browser if you wish to use Gsheet.</noscript>

        <?php
        if (isset($_POST['createAdmin'])) {
            echo <<<EOF
    <form method='post' action='{$_SERVER['PHP_SELF']}' name='adminCreation'>
            <table>
                <tr><td>Username</td><td><input type='text' name='adminUser'></td></tr>
                <tr><td>Password</td><td><input type='password' name='adminPass'></td></tr>
                <tr><td>Re-type password</td><td><input type='password' name='adminPassConfirm'></td></tr>
                <tr><td><input type='button' value='Submit' name='adminSend' onClick='checkPasswords()'></td></tr>
            </table>
    </form>
EOF;
            die();
        }

        if (isset($_POST['adminUser'])) {
            $adminUser = $_POST['adminUser'];
            $adminPass = $_POST['adminPass'];
            $adminPassConfirm = $_POST['adminPassConfirm'];

            include ('connect.php');

            if ($adminPass != $adminPassConfirm) {
                echo "Password fields didn't match!";
            } else {
                $hashPass = md5($adminPass);
                $myquery = "INSERT INTO Users (username, password, userclass) VALUES ('$adminUser', '$hashPass', '1')";
                $query = $db->prepare($myquery);
                $query->execute();
                echo "Admin account created with the username $adminUser. <br>";
                echo "Gsheet is now ready for use!";
                die();
            }
        }

        if (isset($_POST['resetCreds'])) {
            unlink("credentials");
        }

        if (isset($_POST['submitCreds'])) {
            $File = "credentials";
            $fh = fopen($File, 'w') or die("Can't open file");

            $stringData = "db = " . $_POST['dbName'] . "\n" . "user =  " . $_POST['user'] . "\n" . "pass = " . $_POST['pass'];
            fwrite($fh, $stringData);
            fclose($fh);
        }

        function getLogin() {

            echo <<<EOF
    <form method='post' action='{$_SERVER['PHP_SELF']}'>
    
    <table>
    <tr><td>Name of the database:</td><td><Input type='text' name='dbName' size='15'></td></tr>
    <tr><td>Username:</td><td><Input type='text' name='user' size='15'></td></tr>
    <tr><td>Password:</td><td><Input type='password' name='pass' size='15'></td></tr>
    <tr><td><input type='submit' name='submitCreds'></td></tr>
    </table>
 
</form>
EOF;
        }

        function createDB() {
            echo "Creating tables...";
            createTables();
            echo "done<br>";
            echo "Reading advantages...";
            insertAdvantages();
            echo "done<br>";
            echo "Database successfully created! Please create yourself an admin account now.";
            echo "<form method='post'><input type='submit' name='createAdmin' value='Create admin account'></form>";
        }

        function createTables() {

            // Luo tietokantataulut

            include ('connect.php');

            $myquery = "        
        
    CREATE TABLE Users (
    Username VARCHAR(20) NOT NULL,
    Password VARCHAR(50) NOT NULL,
    Userclass INTEGER NOT NULL DEFAULT '0',
    PRIMARY KEY (Username)
    ) ENGINE=InnoDB;
    
    CREATE TABLE Campaigns (
    Campaign_id INTEGER NOT NULL AUTO_INCREMENT,
    CampOwner VARCHAR(20) NOT NULL,
    CampName VARCHAR(50) NOT NULL,
    CampDesc TEXT,
    PRIMARY KEY (Campaign_id),
    FOREIGN KEY (CampOwner) references Users(Username)
    ) ENGINE=InnoDB;
        
    CREATE TABLE Characters (
    Char_id INTEGER NOT NULL AUTO_INCREMENT,
    CharOwner VARCHAR(20) NOT NULL,
    Campaign INTEGER,
    CharName VARCHAR(50) NOT NULL,
    CharDesc VARCHAR(300),
    HitsTaken INTEGER DEFAULT '0',
    Fatigue INTEGER DEFAULT '0',
    ST INTEGER NOT NULL DEFAULT '10',
    DX INTEGER NOT NULL DEFAULT '10', 
    IQ INTEGER NOT NULL DEFAULT '10',
    HT INTEGER NOT NULL DEFAULT '10',
    ActiveDefense VARCHAR(10),
    PassiveDefense VARCHAR(10),
    CharNotes TEXT,
    UnusedPoints INTEGER DEFAULT '100',
    PRIMARY KEY (Char_id),
    FOREIGN KEY (CharOwner) references Users(Username),
    FOREIGN KEY (Campaign) references Campaigns(Campaign_id)
    ) ENGINE=InnoDB;
    
    CREATE TABLE Skills (
    SkillName VARCHAR(50) NOT NULL,
    SkillType VARCHAR(1) NOT NULL, 
    SkillDiff VARCHAR(10) NOT NULL,
    SkillDefault VARCHAR(15) NOT NULL,
    SkillDesc TEXT,
    PRIMARY KEY (SkillName)
    ) ENGINE=InnoDB;
   
    CREATE TABLE Attributes (
    AttrName VARCHAR(50) NOT NULL,
    AttrType VARCHAR(1) NOT NULL, 
    AttrPoints VARCHAR(50) NOT NULL,
    AttrDesc TEXT,
    PRIMARY KEY (AttrName)
    ) ENGINE=InnoDB;
    
    CREATE TABLE Items (
    CharItem_id INTEGER NOT NULL,
    ItemName VARCHAR(50) NOT NULL,
    ItemType VARCHAR(20),
    ItemWeight INTEGER,
    ItemValue INTEGER,
    PRIMARY KEY (CharItem_id),
    FOREIGN KEY (CharItem_id) references Characters(Char_id)
    ) ENGINE=InnoDB;
    
    CREATE TABLE SkillList (
    CharSkill_id INTEGER NOT NULL,
    Skill_name VARCHAR(50) NOT NULL,
    SkillPoints INTEGER NOT NULL,
    PRIMARY KEY (CharSkill_id),
    FOREIGN KEY (CharSkill_id) references Characters(Char_id),
    FOREIGN KEY (Skill_name) references Skills (SkillName)
    ) ENGINE=InnoDB;
    
    CREATE TABLE EquippedWeapons (
    CharWeapon_id INTEGER NOT NULL,
    WeaponName VARCHAR(30) NOT NULL,
    DamageType VARCHAR(5),
    DamageAmount VARCHAR(5),
    WeaponNotes VARCHAR(50),
    PRIMARY KEY (CharWeapon_id),
    FOREIGN KEY (CharWeapon_id) references Characters(Char_id)
    ) ENGINE=InnoDB;
    
    CREATE TABLE AttributeList (
    CharAttr_id INTEGER NOT NULL,
    Attr_name VARCHAR(50) NOT NULL,
    Attr_points INTEGER NOT NULL,
    PRIMARY KEY (CharAttr_id),
    FOREIGN KEY (CharAttr_id) references Characters(Char_id),
    FOREIGN KEY (Attr_name) references Attributes(AttrName)
    ) ENGINE=InnoDB";

            $query = $db->prepare($myquery);
            $query->execute();
        }

        function insertAdvantages() {

            include ('connect.php');

            $advantages = simplexml_load_file("xml/advantages.xml");

            try {
                $db->beginTransaction();


                foreach ($advantages as $advantage) {
                    $parsedName = addslashes($advantage->name);
                    $parsedDesc = addslashes($advantage->description);
                    $parsedPoints = addslashes($advantage->points);
                    $myquery = "INSERT INTO  `$database`.`attributes` (
                `AttrName` ,
                `AttrType` ,
                `AttrPoints` ,
                `AttrDesc`) VALUES (
                '$parsedName',  'A',  '$parsedPoints',  '$parsedDesc'
           );";

                    $query = $db->prepare($myquery);
                    $query->execute();
                }

                $db->commit(); // muutosten hyväksyntä
            } catch (PDOException $e) {
                $db->rollBack(); // muutosten peruutus
                die("VIRHE: " . $e->getMessage());
            }
        }
        ?>



        <h1>Gsheet - installation</h1>
        <?php
        if (file_exists("credentials")) {
            include ('connect.php');
            try {
                $testquery = "SELECT * FROM Users";
                $query = $db->prepare($testquery);
                $query->execute();
                echo "Database connection successful!";
            } catch (PDOException $e) {
                echo "No database detected! Trying to create a new database...<br>";
                createDB();
            }
        } else {
            if (!isset($_POST['submitCreds'])) {
                getLogin();
            }
        }
        ?>
    </body>
</html>

