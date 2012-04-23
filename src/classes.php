<?php

class Character {

    private $charStats;
    private $charAdvantages;
    private $charDisadvantages;
    private $quirks;
    private $ActiveDef;
    private $PassiveDefPD;
    private $PassiveDefDR;

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
        

        // Read advantages from the DB
        
        $this->readAttributes();
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

        $query = "SELECT al.Attr_name, al.Attr_points, a.AttrType 
            FROM attributelist AS al, attributes AS a 
            WHERE al.Attr_Name = a.AttrName 
            AND al.CharAttr_id = ?";
        $myquery = $db->prepare($query);
        $myquery->bindValue(1, $this->charStats->Char_id);
        $myquery->execute();
        while ($result = $myquery->fetchObject()) {
            if ($result->AttrType == 'A') {
                $this->charAdvantages[] = new Attribute($result->Attr_name, true, $result->Attr_points);
            } else {
                $this->charDisadvantages[] = new Attribute($result->Attr_name, false, $result->Attr_points);
            }
        }
    }

    public function getAttributes($type) {
        if ($type == "adv") {
            return $this->charAdvantages;
        } else {
            return $this->charDisadvantages;
        }
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

    function drawSkillButtons($skill) {
        $skillfield = $skill . "Field";
        $skillButton = $skill . "Button";
        if ($this->editable) {
            echo "<input type=\"button\" name=\"$skillButton\" value=\"+\" size=12 class=minibutton onClick=\"increaseSkill('$skillfield')\">";
            echo "<input type=\"button\" name=\"$skillButton\" value=\"-\" size=12 class=minibutton onClick=\"decreaseSkill('$skillfield')\">";
        }
    }

    function writeIfOwner($string) {
        if ($this->editable) {
            echo $string;
        } 
    }
    
    
    
    

}

class Attribute {

    private $name;
    private $points;
    private $isAdvantage;

    public function __construct($name, $isAdvantage, $points) {
        // luetaan kannasta konstruktoinnin yhteydessä vai sheet - luokan metodissa?
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

}

class Skill {

    private $name;
    private $type;
    private $difficulty;
    private $points;

    public function __construct($name, $type, $difficulty, $points) {

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
        if ($this->points < 0) {
            return "0";
        } else {
            return $this->points;
        }
    }

}

class Item {

    private $name;
    private $weigth;
    private $value;

    public function __construct($name, $weight, $value) {
        $this->name = $name;
        $this->weigth = $weight;
        $this->value = $value;
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

}

?>
