<?php

namespace IgnitionWolf\API;

use Exception;
use IgnitionWolf\API\Routing\Router;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Nwidart\Modules\Support\Stub;

class WolfAPIServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
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

        $this->app['router'] = $this->app->make(Router::class);

        /**
         * Entity validator that tries to find a certain entity ID.
         *
         * Validator usage:
         *
         *      entity:{module}/{entity}
         */
        Validator::extend('entity', function ($attribute, $value, $parameters, $validator) {

            $entity = $parameters[0];

            if (strpos($entity, '/') == false) {
                throw new Exception('Format in entity validator must be: {module}/{entity}.');
            }

            $entity = explode('/', $entity);
            $entity = "Modules\\$entity[0]\\Entities\\$entity[1]";

            if (!class_exists($entity)) {
                throw new Exception(
                    "Class $entity not found. Remember format in entity validator must be: {module}/{entity}."
                );
            }

            $instance = $entity::find($value);
            if ($instance) {
                return true;
            }

            return false;
        });

        Validator::replacer('entity', function ($message, $attribute, $rule, $parameters) {
            $entity = explode('/', $parameters[0]);
            return "$entity[1] not found.";
        });
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'api');

        $this->app->register(ExceptionServiceProvider::class);
    }
}
