<?php
namespace Bagusindrayana\LaravelMaps\Leaflet;

use Bagusindrayana\LaravelMaps\Leaflet\Event\LeafletEvent;

class LeafletMap
{
    public $css = "https://unpkg.com/leaflet@1.7.1/dist/leaflet.css";
    public $js = "https://unpkg.com/leaflet@1.7.1/dist/leaflet.js";
    public $name = "leafletMap";
    public $latLng;
    public $options = [];
    public $components = [];
    public $tileLayer = "https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png";
    private $elemen;
    private $codes;

    public function __construct()
    {   
        $this->elemen = "<div id='{$this->name}'></div>";
        
    }

    public function tileLayer($tileLayer)
    {
        $this->tileLayer = $tileLayer;
        return $this;
    }

    public function latLng($latLng)
    {
        $this->latLng = $latLng;
        return $this;
    }

    public function setView($latLng)
    {
        $this->latLng = $latLng;
        return $this;
    }
    
    public function css($link)
    {
        $this->css = is_array($link)?$link:[$link];
        return $this;
    }

    public function js($link)
    {
        $this->js = is_array($link)?$link:[$link];
        return $this;
    }

    public function name($name)
    {
        $this->name = $name;
        return $this;
    }

    public function options($options)
    {
        $this->options = $options;
        return $this;
    }

    public function map($name,$options = null)
    {
        $this->name($name);
        if($options != null)
        {
            $this->options($options);
        }
        return $this;
    }

    public function marker($latLng,$options = null)
    {   
        if(isset($latLng[0][0]) && is_array($latLng[0][0])){
            
            foreach ($latLng as $marker) {
                $this->addMarker($marker);
            }
        } else {
            $this->addMarker([$latLng,$options]);
            
        }

        return $this;
    }

    private function addMarker($marker)
    {   
        
        if($marker instanceof LeafletMarker){
            $this->components[] = $marker->name('marker'.count($this->components));
        } else {
            $marker = new LeafletMarker($marker[0],$marker[1] ?? null);
            $this->components[] = $marker->name('marker'.count($this->components));
        }
    }

    public function on($eventName,$fun = null)
    {   
        $map = new LeafletMap();
        $map->name($this->name);
        $map->latLng($this->latLng);
        $fun($map);
        
        if($eventName instanceof LeafletEvent){
            $this->components[] = $eventName;
        } else {
            $eventName = new LeafletEvent($eventName);
            $eventName->components = $map->getComponents();
            $this->components[] = $eventName;
        }
        return $this;
        
    }

    public function styles()
    {   

        return  view("laravel-maps::styles",[
            "styles" => $this->css,
        ]);
        
    }

    public function scripts()
    {   
        
        return  view("laravel-maps::scripts",[
            "scripts" => $this->js,
            "codes"=>$this->codes
        ]);
        
    }

    private function generateComponent()
    {   
        // $this->code = "";
        $mapName = $this->name;
        foreach ($this->components as $component) {
            $this->codes .= $component->result($mapName);
        }
        return $this->codes;
    }

    public function getComponents()
    {
        return $this->components;
    }


    public function result()
    {   
        $this->codes .= "
        <script>
            var {$this->name} = L.map('{$this->name}').setView([{$this->latLng[0]}, {$this->latLng[1]}], 13);
            ";
        $this->generateComponent();
        $this->codes .= "
            L.tileLayer('{$this->tileLayer}', {
                attribution: '&copy; <a href=\"https://www.openstreetmap.org/copyright\">OpenStreetMap</a> contributors'
            }).addTo({$this->name});
        </script>
        ";

        return $this->codes;
    }

    public function render()
    {   
        $this->result();
        return  view("laravel-maps::render",[
            "elemen"=>$this->elemen,
            
        ]);
        
    }


}