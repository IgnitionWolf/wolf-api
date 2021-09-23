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

class CommandServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot(): void
    {
        $this->commands([
            ControllerMakeCommand::class,
            RequestMakeCommand::class,
            ModelMakeCommand::class,
            AutomapMakeCommand::class,
            CRUDMakeCommand::class,
            TransformerMakeCommand::class
        ]);

        $this->app->extend('command.request.make', function () {
            return app(RequestMakeCommand::class);
        });

        $this->app->extend('command.controller.make', function () {
            return app(ControllerMakeCommand::class);
        });
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
