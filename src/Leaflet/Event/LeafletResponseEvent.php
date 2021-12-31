<?php
namespace Bagusindrayana\LaravelMaps\Leaflet\Event;

class LeafletResponseEvent extends LeafletEvent {
    
    public function result($mapName = null)
    {   
        $this->codes .= "
        {$mapName}.on('{$this->type}',function(e){\r\n";
        $this->generateComponent($mapName);
        $this->codes .= "});\r\n";
        return $this->codes;
    }
}