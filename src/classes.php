<?php

class Character {

    private $charStats;
    private $ActiveDef;
    private $charAdvantages;

    public function __construct($id) {
        include("connect.php");

        $myquery = $db->prepare('SELECT * FROM Characters WHERE Char_id=?');
        $myquery->bindValue(1, $id);
        $myquery->execute();
        $this->charStats = $myquery->fetchObject();

        $this->ActiveDef = explode("|", $this->charStats->ActiveDefense);


//        $myquery = $db->prepare('SELECT * FROM AttributeList WHERE CharAttr_id=?');
//        $myquery->bindValue(1, $id);
//        $myquery->execute();
//        $result = $myquery->fetchObject();
    }

    public function getStat($stat) {
        if ($stat == 'parry') {
            return $this->ActiveDef[0];
        } else if ($stat == 'block') {
            return $this->ActiveDef[1];
        } else {
            return $this->charStats->$stat;
        }
    }

    public function setStat($stat, $value) {
        $this->charStats->$stat = $value;
    }

    public function getAdvantages() {
        return $this->charAdvantages;
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

    function drawItemSelection($selected) {

        echo "<select name='itemType'>";

        echo "<option></option>";
        if ($selected == "weapon") {
            echo "<option selected=selected>Weapon</option>";
        } else {
            echo "<option>Weapon</option>";
        }
        echo "<option>Protection</option>";
        echo "<option>Misc</option>";
        echo "</select>";
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
