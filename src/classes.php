<?php

class Character {

    private $charStats;
    private $charAdvantages;
    private $charDisadvantages;
    private $items;
    private $quirks;
    private $ActiveDef;
    private $PassiveDefPD;
    private $PassiveDefDR;
    private $equippedWeapons;

    public function __construct($id) {
        include("connect.php");

        // Read basic stuff from the DB

        $myquery = $db->prepare('SELECT * FROM Characters WHERE Char_id=?');
        $myquery->bindValue(1, $id);
        $myquery->execute();
        $this->charStats = $myquery->fetchObject();
        $this->quirks = explode("|", $this->charStats->Quirks);
        $this->ActiveDef = explode("|", $this->charStats->ActiveDefense);
        $this->PassiveDefPD = explode("|", $this->charStats->PassiveDefensePD);
        $this->PassiveDefDR = explode("|", $this->charStats->PassiveDefenseDR);

        $this->readAttributes();
        $this->readItems();
        $this->readEquippedWeapons();
    }

    public function getStat($stat) {
        return $this->charStats->$stat;
    }

    public function getDef($type) {
        if ($type == ' active') {
            return $this->ActiveDef;
        } else if ($type == 'passivePD') {
            return $this->PassiveDefPD;
        } else {
            return $this->PassiveDefDR;
        }
    }

    public function getQuirks() {
        return $this->quirks;
    }

    public function readAttributes() {
        include('connect.php');

        $query = "SELECT * 
                FROM attributelist 
                WHERE CharAttr_id = ?";
        $myquery = $db->prepare($query);
        $myquery->bindValue(1, $this->charStats->Char_id);
        $myquery->execute();
        while ($result = $myquery->fetchObject()) {
            if ($result->Attr_type == 'A') {
                $this->charAdvantages[] = new Attribute($result->Attribute_id, $result->Attr_name, true, $result->Attr_points);
            } else {
                $this->charDisadvantages[] = new Attribute($result->Attribute_id, $result->Attr_name, false, $result->Attr_points);
            }
        }
    }

    public function readItems() {
        include('connect.php');

        $query = "SELECT *
            FROM Items
            WHERE CharItem_id = ?";
        $myquery = $db->prepare($query);
        $myquery->bindValue(1, $this->charStats->Char_id);
        $myquery->execute();
        while ($result = $myquery->fetchObject()) {
            $this->items[] = new Item($result->Item_id, $result->ItemName, $result->ItemWeight, $result->ItemValue, $result->ItemType);
        }
    }

    public function readEquippedWeapons() {
        include('connect.php');

        $query = "SELECT *
            FROM EquippedWeapons
            WHERE CharWeapon_id = ?";
        $myquery = $db->prepare($query);
        $myquery->bindValue(1, $this->charStats->Char_id);
        $myquery->execute();
        while ($result = $myquery->fetchObject()) {
            $this->equippedWeapons[] = new EquippedWeapon($result->EquippedWeapon_id, $result->WeaponName, $result->DamageType, $result->DamageAmount, $result->WeaponNotes);
        }
    }

    public function readSkills() {
        
    }

    public function getAttributes($type) {
        if ($type == "adv") {
            return $this->charAdvantages;
        } else {
            return $this->charDisadvantages;
        }
    }

    public function getItems() {
        return $this->items;
    }

    public function getEquippedWeapons() {
        return $this->equippedWeapons;
    }

}

class Sheet {

    private $editable; // onko lukija omistaja

    public function __construct($editable) {
        $this->editable = $editable;
    }

    public function readOnly() {
        if (!$this->editable) {
            echo "readonly=\"readonly\"";
        }
    }

    public function disabledSelect() {
        if (!$this->editable) {
            echo "disabled='yes'";
        }
    }

    function drawButtons($name) {
        $namefield = $name . "Field";
        $nameButton = $name . "Button";
        if ($this->editable) {
            echo "<td><input type=\"button\" name=\"$nameButton\" value=\"+\" size=12 class=minibutton onClick=\"increaseValue('$namefield')\"><br>";
            echo "<input type=\"button\" name=\"$nameButton\" value=\"-\" size=12 class=minibutton onClick=\"decreaseValue('$namefield')\"></td>";
        }
    }

    function writeIfOwner($string) {
        if ($this->editable) {
            echo $string;
        }
    }

}

class Attribute {

    private $id;
    private $name;
    private $points;
    private $isAdvantage;

    public function __construct($id, $name, $isAdvantage, $points) {

        $this->id = $id;
        $this->name = $name;
        $this->isAdvantage = $isAdvantage;
        $this->points = $points;
    }

    public function getName() {
        return $this->name;
    }

    public function getPoints() {
        return $this->points;
    }

    public function isAdvantage() {
        return $this->isAdvantage;
    }

    public function getId() {
        return $this->id;
    }

}

class Skill {

    private $id;
    private $name;
    private $type;
    private $difficulty;
    private $points;

    public function __construct($id, $name, $type, $difficulty, $points) {

        $this->id = $id;
        $this->name = $name;
        $this->type = $type;
        $this->difficulty = $difficulty;
        $this->points = $points;
    }

    public function getName() {
        return $this->name;
    }

    public function getType() {
        return $this->type;
    }

    public function getDifficulty() {
        return $this->difficulty;
    }

    public function getPoints() {
        return $this->points;
    }

    public function getId() {
        return $this->id;
    }

}

class Item {

    private $id;
    private $name;
    private $weigth;
    private $value;
    private $type;

    public function __construct($id, $name, $weight, $value, $type) {

        $this->id = $id;
        $this->name = $name;
        $this->weigth = $weight;
        $this->value = $value;
        $this->type = $type;
    }

    public function getName() {
        return $this->name;
    }

    public function getType() {
        return $this->type;
    }

    public function getWeight() {
        return $this->weigth;
    }

    public function getValue() {
        return $this->value;
    }

    public function getId() {
        return $this->id;
    }

}

class EquippedWeapon {

    private $id;
    private $name;
    private $damageType;
    private $damageAmount;
    private $notes;

    public function __construct($id, $name, $damageType, $damageAmount, $notes) {
        $this->id = $id;
        $this->name = $name;
        $this->damageType = $damageType;
        $this->damageAmount = $damageAmount;
        $this->notes = $notes;
    }

    public function getName() {
        return $this->name;
    }

    public function getDamageType() {
        return $this->damageType;
    }

    public function getDamageAmount() {
        return $this->damageAmount;
    }

    public function getNotes() {
        return $this->notes;
    }

    public function getId() {
        return $this->id;
    }

}

?>
