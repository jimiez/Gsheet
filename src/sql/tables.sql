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
    CampPoints INTEGER,
    PRIMARY KEY (Campaign_id),
    FOREIGN KEY (CampOwner) references Users(Username)
    ON DELETE CASCADE ON UPDATE RESTRICT
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
    ActiveDefense VARCHAR(10) DEFAULT '0|0',
    PassiveDefensePD VARCHAR(20) DEFAULT '0|0|0|0|0|0',
    PassiveDefenseDR VARCHAR(20) DEFAULT '0|0|0|0|0|0',
    Quirks VARCHAR(500) DEFAULT '||||',
    CharNotes TEXT,
    UnusedPoints INTEGER DEFAULT '100',
    PRIMARY KEY (Char_id),
    FOREIGN KEY (CharOwner) references Users(Username)
    ON DELETE CASCADE ON UPDATE RESTRICT,
    FOREIGN KEY (Campaign) references Campaigns(Campaign_id)
    ) ENGINE=InnoDB;
    
    CREATE TABLE Skills (
    SkillName VARCHAR(50) NOT NULL,
    SkillType VARCHAR(1) NOT NULL, 
    SkillDiff VARCHAR(10) NOT NULL,
    SkillDefault VARCHAR(50) NOT NULL,
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
    Item_id INTEGER NOT NULL AUTO_INCREMENT,
    CharItem_id INTEGER NOT NULL,
    ItemName VARCHAR(50),
    ItemType VARCHAR(20),
    ItemWeight VARCHAR(5),
    ItemValue VARCHAR(20),
    PRIMARY KEY (Item_id),
    FOREIGN KEY (CharItem_id) references Characters(Char_id)
    ON DELETE CASCADE ON UPDATE RESTRICT
    ) ENGINE=InnoDB;
    
    CREATE TABLE SkillList (
    Skill_id INTEGER NOT NULL AUTO_INCREMENT,
    CharSkill_id INTEGER,
    Skill_name VARCHAR(50),
    SkillPoints VARCHAR(10),
    PRIMARY KEY (Skill_id),
    FOREIGN KEY (CharSkill_id) references Characters(Char_id)
    ON DELETE CASCADE ON UPDATE RESTRICT,
    FOREIGN KEY (Skill_name) references Skills (SkillName)
    ON DELETE CASCADE ON UPDATE RESTRICT
    ) ENGINE=InnoDB;
    
    CREATE TABLE EquippedWeapons (
    EquippedWeapon_id INTEGER NOT NULL AUTO_INCREMENT,
    CharWeapon_id INTEGER NOT NULL,
    WeaponName VARCHAR(30),
    DamageType VARCHAR(15),
    DamageAmount VARCHAR(5),
    WeaponNotes VARCHAR(50),
    PRIMARY KEY (EquippedWeapon_id),
    FOREIGN KEY (CharWeapon_id) references Characters(Char_id)
    ON DELETE CASCADE ON UPDATE RESTRICT
    ) ENGINE=InnoDB;
    
    CREATE TABLE AttributeList (
    Attribute_id INTEGER NOT NULL AUTO_INCREMENT,
    CharAttr_id INTEGER NOT NULL,
    Attr_name VARCHAR(50),
    Attr_points VARCHAR(10),
    Attr_type VARCHAR(1),
    PRIMARY KEY (Attribute_id),
    FOREIGN KEY (CharAttr_id) references Characters(Char_id)
    ON DELETE CASCADE ON UPDATE RESTRICT
    ) ENGINE=InnoDB";