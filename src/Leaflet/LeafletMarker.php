<?php
namespace Bagusindrayana\LaravelMaps\Leaflet;

class LeafletMarker
{
    public $name = "marker";
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
        if(isset($this->options) && count($this->options) > 0){
            $this->options = array_merge($this->options,$options);
        } else {
            $this->options = $options;
        }
        return $this;
    }

    public function addTo(LeafletMap $leafletMap)
    {
        $leafletMap->marker($this);
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

    public function icon($options)
    {
        $this->codes .= "var ".$this->name."Icon = L.icon(".json_encode($options).");\r\n";
        $this->options(['icon' => $this->name."Icon"]);
        return $this;
    }

    public function generateComponent()
    {   
        $mapName = $this->name;
        foreach ($this->components as $component) {
            if(is_string($component)){
                $this->codes .= $component;
            } else {
                $this->codes .= $component->result($mapName);
            }
            
        }
        return $this->codes;
    }

    public function result($mapName)
    {   
        if(is_string($this->latLng)){
            $this->codes .= "var {$this->name} = L.marker({$this->latLng}".($this->options?",".json_encode($this->options):"").");\r\n";
        } else {
            $this->codes .= "var {$this->name} = L.marker(".json_encode($this->latLng).($this->options?",".json_encode($this->options):"").");\r\n";
        }
        if(isset($this->options['icon'])){
            $this->codes .= "{$this->name}.setIcon(".$this->options['icon'].");\r\n";
        }
        if($mapName){
            $this->codes .= "{$this->name}.addTo({$mapName});\r\n";
        }
        $this->generateComponent();
        
        return $this->codes;
    }


}