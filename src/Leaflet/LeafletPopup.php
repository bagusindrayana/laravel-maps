<?php
namespace Bagusindrayana\LaravelMaps\Leaflet;

use Bagusindrayana\LaravelMaps\RawJs;

class LeafletPopup
{   
    public $name = "popup";
    public $contents;
    private $codes;
    public $latlng;
    public $openPopup;
    public $openOn;
    public $bind;

    public function __construct($contents = null)
    {
        if($contents){
            $this->contents($contents);
        }
    }

    public function name($name)
    {
        $this->name = $name;
        return $this;
    }

    public function setLatLng($latlng)
    {
        $this->latlng = $latlng;
        return $this;
    }

    public function latlng($latlng)
    {
        $this->latlng = $latlng;
        return $this;
    }

    public function setContent($contents)
    {
        return $this->contents($contents);
    }

    public function openPopup()
    {
        $this->openPopup = true;
        return $this;
    }

    public function openOn($map)
    {
        if(is_object($map)){
            $map->components[] = $this;
            $this->openOn = $map->name;
        } else if(is_string($map)) {
            $this->openOn = $map;
        }
        return $this;
    }


    public function contents($contents)
    {   
        $this->contents = ($contents instanceof RawJs)?$contents->result():"`$contents`";
        return $this;
    }

    public function result($markerName = null)
    {   
        $this->codes .= "var {$this->name} = L.popup().setContent(".$this->contents.");\r\n";
        if($this->bind){
            $this->codes .= "{$markerName}.bindPopup(".$this->name.");\r\n";
        }
        if($this->latlng){
            $this->codes .= "{$this->name}.setLatLng(".json_encode($this->latlng).");\r\n";
        }
        if($this->openPopup){
            $this->codes .= "{$this->name}.openPopup();\r\n";
        }
        if($this->openOn){
            $this->codes .= "{$this->name}.openOn({$this->openOn});\r\n";
        }
        return $this->codes;
    }


}