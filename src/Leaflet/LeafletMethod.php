<?php
namespace Bagusindrayana\LaravelMaps\Leaflet;

use Bagusindrayana\LaravelMaps\Leaflet\Event\LeafletEvent;
use Bagusindrayana\LaravelMaps\RawJs;

class LeafletMethod {
    public $zoom = 10;
    public $latLng;
    public $name;
    public $components = [];
    public $codes;
    private $eventType = [
        "\Bagusindrayana\LaravelMaps\Leaflet\Event\LeafletEvent"=>[
            'unload',
            'viewreset',
            'load',
            'zoomstart',
            'movestart',
            'zoom',
            'move',
            'zoomend',
            'moveend',
        ],
        "\Bagusindrayana\LaravelMaps\Leaflet\Event\LeafletMouseEvent"=>[
            'click',
            'dblclick',
            'mousedown',
            'mouseup',
            'mouseover',
            'mouseout',
            'mousemove',
            'contextmenu',
        ],
    ];



    public function name($name)
    {
        $this->name = $name;
        return $this;
    }

    public function marker($latLng)
    {   
        if($latLng instanceof LeafletMarker){
            $this->addMarker($latLng);
        } else {
            if(is_array($latLng)){
                if(count($latLng) == 2 && isset($latLng[0]) && isset($latLng[1]) && !is_array($latLng[0]) && !is_array($latLng[1])){
                    $this->addMarker($latLng);
                } else {
                    foreach ($latLng as $key => $arr) {
                        if(!is_string($key)){
                            $this->addMarker($arr);
                        } else if($arr instanceof LeafletMarker){
                            $this->addMarker($arr);
                        } else {
                            $marker = new LeafletMarker($arr);
                            $marker->name($key);
                            $this->addMarker($marker);
                        }
                        
                    }
                }
            }
            
        }

        return $this;
    }

    public function addMarker($marker)
    {   
        
        if($marker instanceof LeafletMarker){
            $this->components[] = $marker;
        } else {
            $marker = new LeafletMarker($marker);
            $marker->name('marker'.count($this->components));
            $this->components[] = $marker;
        }
    }

    public function on($eventName,$fun = null)
    {   
        if(is_object($eventName)){
            $this->components[] = $eventName;
        } else {
            foreach ($this->eventType as $key => $value) {
                foreach ($value as $en) {
                    if($eventName == $en){
                        $eventName = new $key($eventName);
                        $this->components[] = $eventName;
                    }
                }
            }
            
        }
        if(!isset($eventName)){
            $eventName = new LeafletEvent($eventName);
            $this->components[] = $eventName;
        }
        $eventName->name($this->name);
        $fun($eventName);
        return $this;
        
    }

    public function rawJs($js)
    {
        if($js instanceof RawJs){
            $this->components[] = $js;
        } else {
            $js = new RawJs($js);
            $this->components[] = $js;
        }
        return $this;
    }

    public function setMethod($name,$arg = null,$options = null)
    {
        $argFormat = null;
        if(is_array($arg)){
            $argFormat = json_encode($arg);
        } else {
            $argFormat = $arg;
        }
        if($argFormat){
            $this->components[] = "{$this->name}.{$name}($argFormat".($options?",".json_encode($options):"").");\r\n";
        } else {
            $this->components[] = "{$this->name}.{$name}(".($options?",".json_encode($options):"").");\r\n";
        }
        return $this;
    }

    public function setView($latLng,$zoom = null)
    {   
        $this->latLng = $latLng;
        if($zoom){
            $this->setZoom($zoom);
        }
        return $this;
    }

    public function setZoom($zoom)
    {
        $this->zoom = $zoom;
        return $this;
    }

    public function zoomIn($args,$options = null)
    {
        $this->setMethod("zoomIn",$args,$options);
        return $this;
    }

    public function zoomOut($args,$options = null)
    {
        $this->setMethod("zoomOut",$args,$options);
        return $this;
    }

    public function setZoomAround($args,$options = null)
    {
        $this->setMethod("setZoomAround",$args,$options);
        return $this;
    }

    public function fitBounds($args,$options = null)
    {
        $this->setMethod("fitBounds",$args,$options);
        return $this;
    }
    
    public function fitWorld($options = null)
    {
        $this->setMethod("fitWorld",null,$options);
        return $this;
    }

    public function panTo($args,$options = null)
    {
        $this->setMethod("panTo",$args,$options);
        return $this;
    }

    public function panBy($args,$options = null)
    {
        $this->setMethod("panBy",$args,$options);
        return $this;
    }

    public function flyTo($args,$options = null)
    {
        $this->setMethod("flyTo",$args,$options);
        return $this;
    }

    public function flyToBounds($args,$options = null)
    {
        $this->setMethod("flyToBounds",$args,$options);
        return $this;
    }

    public function locate($options = null)
    {
        $this->setMethod('locate',null,$options);
        return $this;
    }

    public function addPopup($contents,$latLng)
    {
        $popup = new LeafletPopup();
        $popup->latlng($latLng)
        ->contents($contents)
        ->openOn($this);
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

    public function getComponents()
    {
        return $this->components;
    }


    public function result($mapName = null)
    {   
        $this->generateComponent();
        return $this->codes;
    }
}