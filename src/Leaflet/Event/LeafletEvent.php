<?php
namespace Bagusindrayana\LaravelMaps\Leaflet\Event;

class LeafletEvent {
    public $type;
    public $target;
    public $sourceTarget;
    public $propagatedFrom;
    public $components = [];
    private $codes;

    public function __construct($type, $target = null, $sourceTarget = null, $propagatedFrom = null)
    {
        $this->type = $type;
        $this->target = $target;
       
        $this->sourceTarget = $sourceTarget;
        $this->propagatedFrom = $propagatedFrom;
    }

    public function getComponents()
    {
        return $this->components;
    }

    private function generateComponent($mapName)
    {   
        foreach ($this->components as $component) {
            $this->codes .= $component->result($mapName);
        }
        return $this->codes;
    }

    public function result($mapName)
    {   
        $this->codes .= "
        {$mapName}.on('{$this->type}',function({";
        $this->generateComponent($mapName);
        $this->codes .= "});\r\n";
        return $this->codes;
    }
}