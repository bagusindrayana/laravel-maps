Demo : https://laravel-maps-demo.herokuapp.com/

### Installation

```bash
composer require bagusindrayana/laravel-maps
```

Add LaravelMapServiceProvider::class to config/app.php
```php
    'providers'=>[
        //....

        Bagusindrayana\LaravelMaps\LaravelMapsServiceProvider::class,

        //...
    ],
    
```

publish provider
```bash
php artisan vendor:publish --provider=Bagusindrayana\LaravelMaps\LaravelMapsServiceProvider
```

### Usage

in controller

```php
$map = LaravelMaps::leaflet('map')
->setView([51.505, -0.09], 13);

return view('your-view',compact('map'));
```

in view
```html
<html>
<head>
    <title>My Map</title>
    {!! @$map->styles() !!}
</head>
<body>
    {!! @$map->render() !!}
    {!! @$map->scripts() !!}  
</body>
</html>
```



## Leaflet

### Features
- marker
- circle
- polygon
- geojson
- basic event and method

### Basic Usage

```php
//'map' is variable name will be use in javascript code
$map = LaravelMaps::leaflet('map')
->setView([51.505, -0.09], 13)
->addMarker(function(LeafletMarker $marker){
    return $marker
    ->latLng([51.5, -0.09])
    ->bindPopup('<b>Hello world!</b><br>I am a popup.');
})
->addCircle(function(LeafletCircle $circle){
    return $circle
    ->latLng([51.508, -0.11])
    ->options([
        'radius'=>500,
        'color'=>'red',
        'fillColor'=>'#f03',
        'fillOpacity'=>0.5
    ])
    ->bindPopup("I am a circle.");
})
->addPolygon(function(LeafletPolygon $polygon){
    return $polygon
    ->latLng([
        [51.509, -0.08],
        [51.503, -0.06],
        [51.51, -0.047]
    ])
    ->bindPopup("I am a polygon.");                
})
->addPopup("I am a standalone popup.",[51.513, -0.09]);
```

### Method & Event

method are dynamic so you can use most method from original leaflet https://leafletjs.com/reference.html#map-method
argument or parameter in method can be array,Closure,string,and RawJs class



## Mapbox

### Features
- marker
- geojson
- basic event and method

### Basic Usage

```php
//'map' is variable name will be use in javascript code
$map = LaravelMaps::mapbox('map',[
    "center"=>[106.827293,-6.174465],
    "zoom"=>13,
]);

$map->on('load',function($m){
    $m->addMarker(function(MapboxMarker $marker){
        return $marker
        ->lngLat([51.5, -0.09])
        ->setPopup('<b>Hello world!</b><br>I am a popup.');
    });
});
```

### Method & Event

method are dynamic so you can use most method from original mapbox https://docs.mapbox.com/mapbox-gl-js/api/map/#map-instance-members
argument or parameter in method can be array,Closure,string,and RawJs class
