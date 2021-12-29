<?php

namespace Bagusindrayana\LaravelMaps;

use Illuminate\Support\ServiceProvider;

class LaravelMapsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/resources/config/laravel-maps.php', 'laravel-maps');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/resources/config/laravel-maps.php' => config_path('laravel-maps.php'),
        ]);

        $this->loadViewsFrom(__DIR__.'/resources/views', 'laravel-maps');
    }
}
