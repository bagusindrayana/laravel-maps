<?php
namespace Bagusindrayana\LaravelMaps\Leaflet\Event;

use Bagusindrayana\LaravelMaps\Leaflet\LeafletMethod;

class LeafletEvent extends LeafletMethod {
    public $type;
    public $target;
    public $sourceTarget;
    public $propagatedFrom;

    public function __construct($type, $target = null, $sourceTarget = null, $propagatedFrom = null)
    {
        $this->type = $type;
        $this->target = $target;
       
        $this->sourceTarget = $sourceTarget;
        $this->propagatedFrom = $propagatedFrom;
    }


    public function result($mapName = null)
    {   
        $this->name = $mapName;
        $this->codes .= "
        {$mapName}.on('{$this->type}',function(){\r\n";
        $this->generateComponent($mapName);
        $this->codes .= "});\r\n";
        return $this->codes;
    }
}