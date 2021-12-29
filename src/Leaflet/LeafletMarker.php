<?php
namespace Bagusindrayana\LaravelMaps\Leaflet;

class LeafletMarker
{
    public $name = "marker";
    public $latLng;
    public $options;
    private $codes;

    public function __construct($latLng,$options = null)
    {
        $this->latLng = $latLng;
        if($options){
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
        $leafletMap->marker($this);
        return $this;
    }

    public function result($mapName)
    {
        $this->codes .= "
var {$this->name} = L.marker([{$this->latLng[0]}, {$this->latLng[1]}],".json_encode($this->options).").addTo({$mapName});\r\n";
        return $this->codes;
    }


}