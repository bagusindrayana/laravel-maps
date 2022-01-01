<?php
namespace Bagusindrayana\LaravelMaps\Mapbox\Event;

use Bagusindrayana\LaravelMaps\Mapbox\MapboxMethod;

class MapboxEvent extends MapboxMethod {
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
        $args = [];
        if(is_array($this->type)){
            foreach ($this->type as $type) {
                $args[] = "`".$type."`";
            }
        } else {
            $args[] = "`".$this->type."`";
        }
        $this->codes .= "
        {$mapName}.on(".implode(",",$args).",function(){\r\n";
        $this->generateComponent($mapName);
        $this->codes .= "});\r\n";
        return $this->codes;
    }
}