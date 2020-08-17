<?php

namespace IgnitionWolf\API;

use IgnitionWolf\API\Commands\Generators\AutomapMakeCommand;
use IgnitionWolf\API\Commands\Generators\CRUDMakeCommand;
use IgnitionWolf\API\Commands\Generators\RequestMakeCommand;
use IgnitionWolf\API\Commands\Generators\TransformerMakeCommand;
use IgnitionWolf\API\Middleware\DebugParameter;
use IgnitionWolf\API\Rules\EntityRule;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Nwidart\Modules\Support\Stub;

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

            $this->commands([]);

            /**
             * Order laravel-modules package generators to use our new stubs.
             * @package nwidart/laravel-modules
             */
            Stub::setBasePath(sprintf("%s/Commands/Generators/stubs", __DIR__));
        }

        $this->app['router']->pushMiddlewareToGroup('api', DebugParameter::class);

        Route::prefix('api')
            ->middleware('api')
            ->namespace('IgnitionWolf\API\Controllers')
            ->group(function () {
                $this->loadRoutesFrom(__DIR__.'/Routes/api.php');
            });

        $this->commands([
            RequestMakeCommand::class,
            CRUDMakeCommand::class,
            TransformerMakeCommand::class,
            AutomapMakeCommand::class
        ]);

        $this->registerRules($this->app, [
            new EntityRule
        ]);
    }

    /**
     * Register the application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'api');

        $this->app->register(ExceptionServiceProvider::class);

        if ($this->app->runningInConsole() && $this->app->environment() === 'local') {
            $this->app->register('Laracasts\Generators\GeneratorsServiceProvider');
        }
    }

    /**
     * Register custom rules found in the Rules namespace.
     */
    private function registerRules(Application &$app, array $rules): void
    {
        foreach ($rules as $rule) {
            $rule($app);
        }
    }
}
