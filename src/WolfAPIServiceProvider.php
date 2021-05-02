<?php

namespace IgnitionWolf\API;

use IgnitionWolf\API\Commands\AutomapMakeCommand;
use IgnitionWolf\API\Commands\ControllerMakeCommand;
use IgnitionWolf\API\Commands\CRUDMakeCommand;
use IgnitionWolf\API\Commands\RequestMakeCommand;
use IgnitionWolf\API\Commands\ModelMakeCommand;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class WolfAPIServiceProvider extends ServiceProvider implements DeferrableProvider
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

        $this->commands([
            ControllerMakeCommand::class,
            RequestMakeCommand::class,
            ModelMakeCommand::class,
            AutomapMakeCommand::class,
            CRUDMakeCommand::class
        ]);

        $this->app->extend('command.request.make', function () {
            return app(RequestMakeCommand::class);
        });

        $this->app->extend('command.controller.make', function () {
            return app(ControllerMakeCommand::class);
        });

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

    /**
     * @return string[]
     */
    public function provides(): array
    {
        return [
            'command.request.make',
            'command.model.make',
            'command.controller.make'
        ];
    }
}
