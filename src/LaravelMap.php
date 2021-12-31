<?php
namespace Bagusindrayana\LaravelMaps;

use Bagusindrayana\LaravelMaps\Leaflet\LeafletMap;
use Bagusindrayana\LaravelMaps\Mapbox\MapboxMap;

class LaravelMap {

    public $provider;

    public static function map($provider = "leaflet")
    {   
        $map = null;
        switch ($provider) {
            case 'leaflet':
                $map = new LeafletMap();
                break;
            case 'mapbox':
                $map = new MapboxMap();
                break;
            default:
                # code...
                break;
        }
        return $map;
    }

    public static function leaflet($name,$options = null)
    {
        return new LeafletMap($name,$options);
    }

    public static function mapbox($name,$options = null)
    {
        return new MapboxMap($name,$options);
    }
}