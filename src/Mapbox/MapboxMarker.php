<?php
namespace Bagusindrayana\LaravelMaps\Mapbox;

class MapboxMarker
{
    public $name = "marker";
    public $lngLat;
    public $options;
    private $codes;
    public $components = [];

    public function __construct($lngLat,$options = null)
    {
        $this->lngLat = $lngLat;
        if($options){
            $this->options = $options;
        }
    }

    public function name($name)
    {
        $this->name = $name;
        return $this;
    }

    public function setLngLat($lngLat)
    {
        return $this->lngLat($lngLat);
    }

    public function lngLat($lngLat)
    {
        $this->lngLat = $lngLat;
        return $this;
    }

    public function options($options)
    {
        $this->options = $options;
        return $this;
    }

    public function addTo(MapboxMap $mapboxMap)
    {
        $mapboxMap->marker($this);
        return $this;
    
    }

    public function setPopup($popup)
    {
        if($popup instanceof MapboxPopup){
            $popup->attachMarker = true;
            $this->components[] = $popup;
        } else {
            $p = new MapboxPopup($popup);
            $p->attachMarker = true;
            $this->components[] = $p;
        }
        return $this;
    }


   

    public function generateComponent()
    {   
        $markerName = $this->name;
        foreach ($this->components as $component) {
            if(is_string($component)){
                $this->codes .= $component;
            } else {
                $this->codes .= $component->result($markerName);
            }
            
        }
        return $this->codes;
    }

    public function result($mapName = null)
    {   
        if(is_string($this->lngLat)){
            $this->codes .= "var {$this->name} = new mapboxgl.Marker(".($this->options?json_encode($this->options):"").").setLngLat(".$this->lngLat.");\r\n";
        } else {
            $this->codes .= "var {$this->name} = new mapboxgl.Marker(".($this->options?json_encode($this->options):"").").setLngLat(".json_encode($this->lngLat).");\r\n";
        }
        if($mapName){
            $this->code .= "{$this->name}.addTo({$mapName});\r\n";
        }
        $this->generateComponent();
        
        return $this->codes;
    }


}