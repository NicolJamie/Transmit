<?php

namespace NicolJamie\Transmit;

use App\Console\Commands\Deploy;
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
            __DIR__.'/../config/transmit.php' => config_path('transmit.php'),
        ], 'transmit');

        if ($this->app->runningInConsole()) {
            $this->commands([
                Deploy::class
            ]);
        }
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
