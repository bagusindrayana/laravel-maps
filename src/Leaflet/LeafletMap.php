<?php
namespace Bagusindrayana\LaravelMaps\Leaflet;

use Bagusindrayana\LaravelMaps\Leaflet\Event\LeafletEvent;

class LeafletMap extends LeafletMethod
{   
    
    public $css;
    public $js;
    public $name = "leafletMap";
    public $options = [];
    public $tileLayer = "https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png";
    private $elemen;

    public function __construct($name = null, $latLng = null, $options = null)
    {   
        $this->css =  config("laravel-maps.leaflet.css");
        $this->js = config("laravel-maps.leaflet.js");
        $this->name = $name ?? $this->name;
        $this->latLng = $latLng ?? $this->latLng;
        $this->options = $options ?? $this->options; 
        
        
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


    public function styles()
    {   

        return  view("laravel-maps::styles",[
            "styles" => is_array($this->css)?$this->css:[$this->css],
        ]);
        
    }

    public function scripts()
    {   
        
        return  view("laravel-maps::scripts",[
            "scripts" => is_array($this->js)?$this->js:[$this->js],
            "codes"=>$this->codes
        ]);
        
    }

    public function result($mapName = null)
    {   
        $this->codes .= "
        <script>
            var {$this->name} = L.map('{$this->name}')";
            $this->generateComponent();
            $this->codes .= "
            {$this->name}.setView(".json_encode($this->latLng).", $this->zoom);
            L.tileLayer('{$this->tileLayer}', {
                attribution: '&copy; <a href=\"https://www.openstreetmap.org/copyright\">OpenStreetMap</a> contributors'
            }).addTo({$this->name});
        </script>";

        return $this->codes;
    }

    public function render()
    {   
        $this->result();
        $this->elemen = "<div id='{$this->name}' style='width:100%;height:100vh;'></div>";
        return  view("laravel-maps::render",[
            "elemen"=>$this->elemen,
            
        ]);
        
    }


}