<?php

class Sheet {

    private $editable; // onko lukija omistaja?
    private $cdata; // hahmon perusomnaisuudet taulukossa
    private $attributes; // taulukko eduille
    private $skills;
    private $quirks;

    public function __construct() {
        $this->editable = true;
        // kaikki menee luultavasti vielä omaan funkkariinsa
        $this->cdata = array();
        $this->cdata['owner'] = "someone";
        $this->cdata['name'] = "testihahmo";
        $this->cdata['appearance'] = "asd asd";
        $this->cdata['campaign'] = null;
        $this->cdata['age'] = 12;
        $this->cdata['height'] = "180 cm";
        $this->cdata['weight'] = "70 kg";
        $this->cdata['race'] = "Human";
        $this->cdata['description'] = "Urhea seikkailija";
        $this->cdata['hitsTaken'] = 0;
        $this->cdata['fatigue'] = 0;
        $this->cdata['st'] = 10;
        $this->cdata['dx'] = 10;
        $this->cdata['iq'] = 10;
        $this->cdata['ht'] = 10;
        $this->cdata['unusedPoints'] = 100;
        $this->attributes = array();
        $this->readAttributes();
    }

    public function getValue($entry) {
        $value = $this->cdata[$entry];
        echo "value=\"$value\"";
    }

    public function getAttributes($type) {
        
        $attributeList = array();

        if ($type == "adv") {
            foreach ($this->attributes as $attr) {
                if ($attr->isAdvantage()) {
                    $attributeList[] = $attr;
                }
            }
        } else {
            foreach ($this->attributes as $attr) {
                if ($attr->isAdvantage() == false) {
                    $attributeList[] = $attr;
                }
            }
        }

        return $attributeList;
    }

    public function setValue($entry, $value) {
        $this->cdata[$entry] = $value;
    }

    public function readOnly() {
        if (!$this->editable) {
            echo "readonly=\"readonly\"";
        }
    }

    public function drawButtons($name) {
        $namefield = $name . "Field";
        $nameButton = $name . "Button";
        if ($this->editable) {
            echo "<td><input type=\"button\" name=\"$nameButton\" value=\"+\" size=12 class=minibutton onClick=\"increaseValue('$namefield')\"><br>";
            echo "<input type=\"button\" name=\"$nameButton\" value=\"-\" size=12 class=minibutton onClick=\"decreaseValue('$namefield')\"></td>";
        }
    }

    private function readAttributes() {

        $advantage = new Attribute("Place holder", true, 10);
        $advantage2 = new Attribute("Yet another", true, 15);
        $disadvantage = new Attribute("Bad breath", false, -10);
        $disadvantage2 = new Attribute("Stinky fist", false, -20);

        $this->attributes[] = $advantage;
        $this->attributes[] = $advantage2;
        $this->attributes[] = $disadvantage;
        $this->attributes[] = $disadvantage2;
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

?>
