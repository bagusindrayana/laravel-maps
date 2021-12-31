<?php
namespace Bagusindrayana\LaravelMaps\Leaflet;

class LeafletCircle
{
    public $name = "circle";
    public $latLng;
    public $options;
    private $codes;
    private $components = [];

    public function __construct($latLng,$options = null)
    {
        $this->latLng = $latLng;
        if($options){
            if(isset($options['name'])){
                $this->name = $options['name'];
            }
            $this->options = $options;
        }
    }

    public function name($name)
    {
        $this->name = $name;
        return $this;
    }

    public function latLng($latLng)
    {
        $this->latLng = $latLng;
        return $this;
    }

    public function options($options)
    {
        $this->options = $options;
        return $this;
    }

    public function addTo(LeafletMap $leafletMap)
    {
        $leafletMap->circle($this);
        return $this;
    
    }

    public function bindPopup($popup)
    {   
        $p = null;
        if($popup instanceof LeafletPopup){
            $p = $popup;
        } else {
            $p = new LeafletPopup($popup);
        }
        $p->bind = true;
        $this->components[] = $p;
        return $this;
    }

    public function generateComponent()
    {   
        $circleName = $this->name;
        foreach ($this->components as $component) {
            if(is_string($component)){
                $this->codes .= $component;
            } else {
                $this->codes .= $component->result($circleName);
            }
            
        }
        return $this->codes;
    }

    public function result($mapName = null)
    {   
        if(is_string($this->latLng)){
            $this->codes .= "var {$this->name} = L.circle({$this->latLng}".($this->options?",".json_encode($this->options):"").");\r\n";
        } else {
            $this->codes .= "var {$this->name} = L.circle(".json_encode($this->latLng).($this->options?",".json_encode($this->options):"").");\r\n";
        }
        if($mapName){
            $this->codes .= "{$this->name}.addTo({$mapName});\r\n";
        }
        $this->generateComponent();
        
        return $this->codes;
    }


}