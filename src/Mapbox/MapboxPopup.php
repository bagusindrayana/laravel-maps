<?php
namespace Bagusindrayana\LaravelMaps\Mapbox;

use Bagusindrayana\LaravelMaps\RawJs;

class MapboxPopup
{   
    public $name = "popup";
    public $htmls;
    private $codes;
    public $lngLat;
    public $addTo;
    public $options;
    public $attachMarker;

    public function __construct($options = null,$args = null)
    {
        if(is_array($options)){
            $this->options = $options;
        } else {
            $this->htmls = $options;
            if(is_array($args)){
                $this->options = $args;
            }
        }
    }

    public function name($name)
    {
        $this->name = $name;
        return $this;
    }

    public function setHTML($htmls)
    {
        return $this->htmls($htmls);
    }
    
    public function setLatLng($lngLat)
    {
        $this->lngLat = $lngLat;
        return $this;
    }

    public function lngLat($lngLat)
    {
        $this->lngLat = $lngLat;
        return $this;
    }

    public function openPopup()
    {
        $this->openPopup = true;
        return $this;
    }

    public function addTo($map)
    {
        if(is_object($map)){
            $map->components[] = $this;
            $this->addTo = $map->name;
        } else if(is_string($map)) {
            $this->addTo = $map;
        }
        return $this;
    }


    public function htmls($htmls)
    {   
        $this->htmls = ($htmls instanceof RawJs)?$htmls->result():"`$htmls`";
        return $this;
    }

    public function result($markerName = null)
    {   
        $this->codes .= "var {$this->name} = new mapboxgl.Popup(".($this->options?json_encode($this->options):"").").setHTML(`".$this->htmls."`);\r\n";
        
        if($this->lngLat){
            $this->codes .= "{$this->name}.setLngLat(".json_encode($this->lngLat).");\r\n";
        }

        if($this->attachMarker && $markerName){
            $this->codes .= "{$markerName}.setPopup({$this->name});\r\n";
        }

        if($this->addTo){
            $this->codes .= "{$this->name}.addTo({$this->addTo});\r\n";
        }
        return $this->codes;
    }


}