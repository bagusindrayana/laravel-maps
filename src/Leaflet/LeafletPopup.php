<?php
namespace Bagusindrayana\LaravelMaps\Leaflet;

class LeafletPopup
{
    public $name = "popup";
    public $contents;
    private $codes;

    public function __construct($contents = null)
    {
        if($contents){
            $this->contents = $contents;
        }
    }

    public function name($name)
    {
        $this->name = $name;
        return $this;
    }


    public function contents($contents)
    {
        $this->contents = $contents;
        return $this;
    }

    public function result()
    {   
        $this->codes .= "var {$this->name} = L.popup.setContent(".$this->contents.");\r\n";
        return $this->codes;
    }


}