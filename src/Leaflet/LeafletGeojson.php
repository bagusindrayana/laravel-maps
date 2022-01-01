<?php
namespace Bagusindrayana\LaravelMaps\Leaflet;

use Bagusindrayana\LaravelMaps\RawJs;

class LeafletGeojson
{
    public $name = "geojson";
    public $feature;
    public $options;
    private $codes;
    private $components = [];
    private $optionMethods = ["pointToLayer","style","onEachFeature","filter","coordsToLatLng"];

    public function __construct($feature,$options = null)
    {
        $this->feature = $feature;
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

    public function feature($feature)
    {
        $this->feature = $feature;
        return $this;
    }

    public function options($options)
    {
        $this->options = $options;
        return $this;
    }

    public function addTo(LeafletMap $leafletMap)
    {
        $leafletMap->geojson($this);
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
        $geojsonName = $this->name;
        foreach ($this->components as $component) {
            if(is_string($component)){
                $this->codes .= $component;
            } else {
                $this->codes .= $component->result($geojsonName);
            }
            
        }
        return $this->codes;
    }

    public function result($mapName = null)
    {   
        foreach ($this->options as $key => $option) {
            if($option instanceof RawJs){
                $this->options[$key] = $option->result();
            }
        }

        $jsonOptions = trim(preg_replace('/\s\s+|\s$/'," ",json_encode($this->options)));
        $string = str_replace(array('\n', "\r"), '', $jsonOptions);
        $string2 = str_replace(array('\n', "\r"), '', trim(preg_replace('/\s+/'," ",'"'.$this->options['onEachFeature'].'"')));
       
        foreach ($this->options as $key => $option) {
            if(in_array($key,$this->optionMethods)){
                
                $jsonOptions = str_replace($string2,$this->options[$key],$string);
            }
        }
       
        
        if(is_string($this->feature)){
            $this->codes .= "var {$this->name} = L.geoJSON({$this->feature}".($this->options?",$jsonOptions":"").");\r\n";
        } else {
            $this->codes .= "var {$this->name} = L.geoJSON(".json_encode($this->feature).($this->options?",$jsonOptions":"").");\r\n";
        }
        if($mapName){
            $this->codes .= "{$this->name}.addTo({$mapName});\r\n";
        }
        $this->generateComponent();
        
        return $this->codes;
    }


}