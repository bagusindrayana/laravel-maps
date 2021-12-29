<?php
namespace Bagusindrayana\LaravelMaps;

class RawJs {
    public $codes;

    public function __construct($codes)
    {
        $this->codes = $codes;
    }

    public function result()
    {
        return $this->codes;
    }
}