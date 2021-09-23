<?php

namespace IgnitionWolf\API;

use IgnitionWolf\API\Commands\AutomapMakeCommand;
use IgnitionWolf\API\Commands\ControllerMakeCommand;
use IgnitionWolf\API\Commands\CRUDMakeCommand;
use IgnitionWolf\API\Commands\RequestMakeCommand;
use IgnitionWolf\API\Commands\ModelMakeCommand;
use IgnitionWolf\API\Commands\TransformerMakeCommand;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class WolfAPIServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole() && $this->app->environment() === 'local') {
            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('api.php'),
            ], 'config');
        }

        $this->loadTranslationsFrom(__DIR__.'/Resources/lang', 'api');
    }

    /**
     * Register the application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'api');

        $this->app->register(ExceptionServiceProvider::class);
    }
}
