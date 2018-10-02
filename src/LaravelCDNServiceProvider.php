<?php

namespace NicolJamie\LaravelCDN;

use Illuminate\Support\ServiceProvider;

class LaravelCDNServiceProvider extends ServiceProvider
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
        ], 'spaces');
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
