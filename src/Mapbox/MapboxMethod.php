<?php
namespace Bagusindrayana\LaravelMaps\Mapbox;

use Bagusindrayana\LaravelMaps\Mapbox\Event\MapboxEvent;
use Bagusindrayana\LaravelMaps\RawJs;
use Closure;
use ReflectionFunction;

class MapboxMethod {
    public $name = "mapboxMap";
    public $options = [];
    public $zoom = 10;
    public $lngLat;
    public $components = [];
    public $codes;
    public $style = "mapbox://styles/mapbox/streets-v11";

    private $eventType = [
        "\Bagusindrayana\LaravelMaps\Mapbox\Event\MapboxEvent"=>[
            'load',
            'viewreset',
            'load',
            'zoomstart',
            'movestart',
            'zoom',
            'move',
            'zoomend',
            'moveend',
        ],
        "\Bagusindrayana\LaravelMaps\Mapbox\Event\MapboxResponseEvent"=>[
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

    public function __construct($name = null, $options = null)
    {   
        
        $this->init($name, $options);
    }

    public function init($name = null, $options = null)
    {   
       
        if(is_array($name)){
            $this->options = $options ?? $this->options;
        } else {
            $this->name = $name ?? $this->name;
            $this->options = $options ?? $this->options;
        }
        return $this;
    }

    public function name($name)
    {
        $this->name = $name;
        return $this;
    }

    public function marker($latLng)
    {   
        if($latLng instanceof MapboxMarker){
            $this->addMarker($latLng);
        } else {
            if(is_array($latLng)){
                if(count($latLng) == 2 && isset($latLng[0]) && isset($latLng[1]) && !is_array($latLng[0]) && !is_array($latLng[1])){
                    $this->addMarker($latLng);
                } else {
                    foreach ($latLng as $key => $arr) {
                        if(!is_string($key)){
                            $this->addMarker($arr);
                        } else if($arr instanceof MapboxMarker){
                            $this->addMarker($arr);
                        } else {
                            $marker = new MapboxMarker($arr);
                            $marker->name($key);
                            $this->addMarker($marker);
                        }
                        
                    }
                }
            }
            
        }

        return $this;
    }

    public function addMarker($marker,$options = null)
    {   
        if(!is_array($marker)){
            try {
                $reflection = new ReflectionFunction($marker);
            } catch (\Throwable $th) {
                //throw $th;
            }
        }
        if(isset($reflection) && $reflection->isClosure()){
            $m = new MapboxMarker([]);
            $this->components[] = $marker($m);
        } else if($marker instanceof MapboxMarker){
            $this->components[] = $marker;
        } else {
            $marker = new MapboxMarker($marker,$options);
            $this->components[] = $marker;
        }
        return $this;
    }

    public function addPopup($contents,$lngLat)
    {
        if($contents instanceof MapboxPopup){
            $this->components[] = $contents;
        } else {
            $popup = new MapboxPopup();
            $popup->lngLat($lngLat)
            ->htmls($contents)
            ->addTo($this);
        }
        return $this;
    }

    public function on($eventName,$fun = null)
    {   
        if(is_object($eventName)){
            $this->components[] = $eventName;
        } else {
            foreach ($this->eventType as $key => $value) {
                foreach ($value as $en) {
                    if(is_array($eventName) && in_array($en,$eventName)){
                        $eventName = new $key($eventName);
                        $this->components[] = $eventName;
                    } else {
                        if($eventName == $en){
                            $eventName = new $key($eventName);
                            $this->components[] = $eventName;
                        }
                    }
                    
                }
            }
            
        }
        if(!is_object($eventName)){
            $eventName = new MapboxEvent($eventName);
            $this->components[] = $eventName;
        }
        $eventName->name($this->name);
        $fun($eventName);
        return $this;
        
    }


    public function __call($method, $args)
	{   
        foreach ($args as $index => $arg) {
            if(is_array($arg)){
                $args[$index] = json_encode($arg);
            } else if($arg instanceof Closure){
                $args[$index] = $arg($this);
            } else if(is_string($arg)){
                $args[$index] = "`".$arg."`";
            } else if($arg instanceof RawJs){
                $args[$index] = $arg->result();
            }
        }
		$this->components[] = $this->name.".$method(".implode(',',$args).");\r\n";
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

   
}