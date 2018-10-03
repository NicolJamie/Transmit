<?php

namespace NicolJamie\Transmit;

use Illuminate\Support\ServiceProvider;

class TransmitServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/cdn.php' => config_path('cdn.php'),
        ], 'cdn');
    }

    /**
     * Register the application services.
     *
     * @return void
     * @throws \Exception
     */
    public function register()
    {
        if (!class_exists('NicolJamie\Spaces\Space')) {
            throw new \Exception('You need to include NicolJamie\Spaces\Space');
        }
    }
}