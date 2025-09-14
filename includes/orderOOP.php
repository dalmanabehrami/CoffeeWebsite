<?php
class Coffee {
    protected $name;
    protected $origin;
    private $roastLevel;

    public function __construct($name, $origin, $roastLevel) {
        $this->name = $name;
        $this->origin = $origin;
        $this->roastLevel = $roastLevel;
    }

    public function getName() { return $this->name; }
    public function getOrigin() { return $this->origin; }
    public function getRoastLevel() { return $this->roastLevel; }
    public function setRoastLevel($roastLevel) { $this->roastLevel = $roastLevel; }

    public function displayInfo() {
        return "Coffee Name: {$this->name}, Origin: {$this->origin}, Roast: {$this->roastLevel}";
    }
}

class SpecialtyCoffee extends Coffee {
    private $flavorNotes;

    public function __construct($name, $origin, $roastLevel, $flavorNotes) {
        parent::__construct($name, $origin, $roastLevel);
        $this->flavorNotes = $flavorNotes;
    }

    public function getFlavorNotes() { return $this->flavorNotes; }

    public function displayInfo() {
        return parent::displayInfo() . ", Flavor Notes: {$this->flavorNotes}";
    }
}
?>