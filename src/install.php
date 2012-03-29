<html>

    <head>
        <title>
            Gsheet installation
        </title>
    </head>
    <body>
        <h1>Gsheet - installation</h1>
        <?php
        if (file_exists("credentials")) {
           
        } else {
            echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "'>";
            echo "Name of the database: <Input type='text' name='dbName' size='10'><br>";
            echo "Username: <Input type='text' name='user' size='10'><br>";
            echo "Password: <Input type='password' name='pass' size='10'><br>";
            echo "<input type='submit'></form>";
        }
        ?>
    </body>
</html>

<?php
if (isset($_POST['dbName'])) {
    $dbName = $_POST['dbName'];
    $user = $_POST['user'];
    $pass = $_POST['pass'];

    echo "Creating tables...";

    $pdo = new PDO("mysql:host=localhost;dbname=$dbName", "$user", "$pass");

    $myquery = "        
        
    CREATE TABLE $dbName.Users (
    Username VARCHAR(20) NOT NULL,
    Password VARCHAR(50) NOT NULL,
    Userclass INTEGER NOT NULL,
    PRIMARY KEY (Username)
    ) ENGINE=InnoDB;
    
    CREATE TABLE $dbName.Campaigns (
    Campaign_id INTEGER NOT NULL AUTO_INCREMENT,
    CampOwner VARCHAR(20) NOT NULL,
    CampName VARCHAR(50) NOT NULL,
    CampDesc TEXT,
    PRIMARY KEY (Campaign_id),
    FOREIGN KEY (CampOwner) references Users(Username)
    ) ENGINE=InnoDB;
        
    CREATE TABLE $dbName.Characters (
    Char_id INTEGER NOT NULL AUTO_INCREMENT,
    CharOwner VARCHAR(20) NOT NULL,
    Campaign INTEGER,
    CharName VARCHAR(50) NOT NULL,
    CharDesc VARCHAR(300),
    HitsTaken INTEGER,
    Fatigue INTEGER,
    ST INTEGER NOT NULL,
    DX INTEGER NOT NULL,
    IQ INTEGER NOT NULL,
    HT INTEGER NOT NULL,
    ActiveDefense VARCHAR(10),
    PassiveDefense VARCHAR(10),
    CharNotes TEXT,
    UnusedPoints INTEGER,
    PRIMARY KEY (Char_id),
    FOREIGN KEY (CharOwner) references Users(Username),
    FOREIGN KEY (Campaign) references Campaigns(Campaign_id)
    ) ENGINE=InnoDB;
    
    CREATE TABLE $dbName.Skills (
    SkillName VARCHAR(50) NOT NULL,
    SkillType VARCHAR(1) NOT NULL, 
    SkillDiff VARCHAR(10) NOT NULL,
    SkillDefault VARCHAR(15) NOT NULL,
    SkillDesc TEXT,
    PRIMARY KEY (SkillName)
    ) ENGINE=InnoDB;
   
    CREATE TABLE $dbName.Attributes (
    AttrName VARCHAR(50) NOT NULL,
    AttrType VARCHAR(1) NOT NULL, 
    AttrPoints VARCHAR(50) NOT NULL,
    AttrDesc TEXT,
    PRIMARY KEY (AttrName)
    ) ENGINE=InnoDB;
    
    CREATE TABLE $dbName.Items (
    CharItem_id INTEGER NOT NULL,
    ItemName VARCHAR(50) NOT NULL,
    ItemType VARCHAR(20),
    ItemWeight INTEGER,
    ItemValue INTEGER,
    PRIMARY KEY (CharItem_id),
    FOREIGN KEY (CharItem_id) references Characters(Char_id)
    ) ENGINE=InnoDB;
    
    CREATE TABLE $dbName.SkillList (
    CharSkill_id INTEGER NOT NULL,
    Skill_name VARCHAR(50) NOT NULL,
    SkillPoints INTEGER NOT NULL,
    PRIMARY KEY (CharSkill_id),
    FOREIGN KEY (CharSkill_id) references Characters(Char_id),
    FOREIGN KEY (Skill_name) references Skills (SkillName)
    ) ENGINE=InnoDB;
    
    CREATE TABLE $dbName.EquippedWeapons (
    CharWeapon_id INTEGER NOT NULL,
    WeaponName VARCHAR(30) NOT NULL,
    DamageType VARCHAR(5),
    DamageAmount VARCHAR(5),
    WeaponNotes VARCHAR(50),
    PRIMARY KEY (CharWeapon_id),
    FOREIGN KEY (CharWeapon_id) references Characters(Char_id)
    ) ENGINE=InnoDB;
    
    CREATE TABLE $dbName.AttributeList (
    CharAttr_id INTEGER NOT NULL,
    Attr_name VARCHAR(50) NOT NULL,
    Attr_points INTEGER NOT NULL,
    PRIMARY KEY (CharAttr_id),
    FOREIGN KEY (CharAttr_id) references Characters(Char_id),
    FOREIGN KEY (Attr_name) references Attribute s(AttrName)
    ) ENGINE=InnoDB";


    $query = $pdo->prepare($myquery);
    $query->execute();
    
    $pdo = null;

    echo "done<br>";
    echo "Saving credentials...";
    writeDetails($dbName, $user, $pass);
    echo "done<br>";
    echo "Reading advantages...";
    insertAdvantages();
    echo "done";
}

function writeDetails($db, $user, $pass) {
    $File = "credentials";
    $fh = fopen($File, 'w') or die("Can't open file");

    $stringData = "db = $db" . "\n" . "user = $user" . "\n" . "pass = $pass";
    fwrite($fh, $stringData);
    fclose($fh);
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