<?php
namespace Bagusindrayana\LaravelMaps;

use Bagusindrayana\LaravelMaps\Leaflet\LeafletMap;
use Bagusindrayana\LaravelMaps\Leaflet\LeafletMethod;

class LaravelMap {

    public $provider;

    public static function map($provider = "leaflet")
    {   
        $map = null;
        switch ($provider) {
            case 'leaflet':
                $map = new LeafletMap();
                break;
            
            default:
                # code...
                break;
        }
        return $map;
    }

    // public function __call($method, $arguments)
    // {
    //     $method = new LeafletMethod($method, $arguments);
    //     return $method;
    // }
}