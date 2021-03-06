<?php
namespace Bagusindrayana\LaravelMaps\Mapbox;

class MapboxMap extends MapboxMethod {
    public $css = ["https://api.mapbox.com/mapbox-gl-js/v2.6.1/mapbox-gl.css"];
    public $js = ["https://api.mapbox.com/mapbox-gl-js/v2.6.1/mapbox-gl.js"];
    public $name = "mapboxMap";
    public $options = [];
    public $zoom = 10;
    public $lngLat;
    public $components = [];
    public $codes;
    public $style = "mapbox://styles/mapbox/streets-v11";
    private $elemen;

    public function __construct($name = null, $options = null)
    {   
        
        $this->init($name, $options);
    }

    public function init($name = null, $options = null)
    {   
        
        $this->css =  config("laravel-maps.mapbox.css") ?? $this->css;
        $this->js = config("laravel-maps.mapbox.js") ?? $this->js;
        if(is_array($name)){
            $this->options = $options ?? $this->options;
        } else {
            $this->name = $name ?? $this->name;
            $this->options = $options ?? $this->options;
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
        $this->result();
        return  view("laravel-maps::scripts",[
            "scripts" => is_array($this->js)?$this->js:[$this->js],
            "codes"=>$this->codes
        ]);
        
    }


    public function result($mapName = null)
    {   
        if(!isset($this->options["style"])){
            $this->options["style"] = $this->style;
        }
        if(!isset($this->options["container"])){
            $this->options["container"] = $this->name;
        }
        $this->codes = "
        <script>
            mapboxgl.accessToken = '".config("laravel-maps.mapbox.mapbox_access_token")."';
            var {$this->name} = new mapboxgl.Map(".json_encode($this->options).");";
            $this->generateComponent();
            $this->codes .= "
        </script>";

        return $this->codes;
    }

    public function render()
    {   
        $this->elemen = "<div id='".($this->options["container"] ?? $this->name)."' style='width:100%;height:100vh;'></div>";
        return  view("laravel-maps::render",[
            "elemen"=>$this->elemen,
            
        ]);
        
    }
}