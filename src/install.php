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
    );
    
    CREATE TABLE $dbName.Campaigns (
    Campaign_id INTEGER NOT NULL AUTO_INCREMENT,
    CampOwner VARCHAR(20) NOT NULL,
    CampName VARCHAR(50) NOT NULL,
    CampDesc VARCHAR(250),
    PRIMARY KEY (Campaign_id),
    FOREIGN KEY (CampOwner) references Users(Username)
    );
        
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
    UnusedPoints INTEGER,
    PRIMARY KEY (Char_id),
    FOREIGN KEY (CharOwner) references Users(Username),
    FOREIGN KEY (Campaign) references Campaigns(Campaign_id)
    );
    
    CREATE TABLE $dbName.Skills (
    SkillName VARCHAR(50) NOT NULL,
    SkillType VARCHAR(1) NOT NULL, 
    SkillDiff VARCHAR(10) NOT NULL,
    SkillDefault VARCHAR(15) NOT NULL,
    SkillDesc VARCHAR(500),
    PRIMARY KEY (SkillName)
    );
   
    CREATE TABLE $dbName.Attributes (
    AttrName VARCHAR(50) NOT NULL,
    AttrType VARCHAR(1) NOT NULL, 
    AttrPoints INTEGER NOT NULL,
    AttrDesc VARCHAR(500),
    PRIMARY KEY (AttrName)
    );
    
    CREATE TABLE $dbName.Items (
    CharItem_id INTEGER NOT NULL,
    ItemName VARCHAR(50) NOT NULL,
    ItemType VARCHAR(20),
    ItemWeight INTEGER,
    ItemValue INTEGER,
    PRIMARY KEY (CharItem_id),
    FOREIGN KEY (CharItem_id) references Characters(Char_id)
    );
    
    CREATE TABLE $dbName.SkillList (
    CharSkill_ id INTEGER NOT NULL,
    Skills_name VARCHAR(50) NOT NULL,
    SkillPoints INTEGER NOT NULL,
    PRIMARY KEY (CharSkill_id),
    FOREIGN KEY (CharSkill_id) references Characters(Char_id),
    FOREIGN KEY (Skills_name) references Skills (SkillName)
    );
    
    CREATE TABLE $dbName.AttributeList (
    CharAttr_id INTEGER NOT NULL,
    Attr_name VARCHAR(50) NOT NULL,
    PRIMARY KEY (CharAttr_id),
    FOREIGN KEY (CharAttr_id) references Characters(Char_id),
    FOREIGN KEY (Attr_name) references Attribute s(AttrName)
    )";   
    
    
    $query = $pdo->prepare($myquery);
    $query->execute();
    
    echo "done";
    
   
}

?>

<html>
    <body>
        <form method="post" action="<? $_SERVER['PHP_SELF'] ?>">
        Name of database: <Input type="text" name="dbName" size="10"><br>
        Username: <Input type="text" name="user" size="10"><br>
        Password: <Input type="password" name="pass" size="10"><br>
        <input type="submit">
        
        </form>
        
    </body>
    
</html>