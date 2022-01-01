<?php
namespace Bagusindrayana\LaravelMaps\Mapbox\Event;

class MapboxResponseEvent extends MapboxEvent {
    
    public function result($mapName = null)
    {   
        $args = [];
        if(is_array($this->type)){
            foreach ($this->type as $type) {
                $args[] = "`".$type."`";
            }
        } else {
            $args[] = "`".$this->type."`";
        }
        $this->codes .= "
        {$mapName}.on(".implode(",",$args).",function(e){\r\n";
        $this->generateComponent($mapName);
        $this->codes .= "});\r\n";
        return $this->codes;
    }
}