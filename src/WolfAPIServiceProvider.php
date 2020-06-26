<?php

namespace IgnitionWolf\API;

use Exception;
use IgnitionWolf\API\Commands\Generators\CRUDMakeCommand;
use IgnitionWolf\API\Commands\Generators\RequestMakeCommand;
use IgnitionWolf\API\Commands\Generators\TransformerMakeCommand;
use IgnitionWolf\API\Middleware\DebugParameter;
use IgnitionWolf\API\Services\RequestValidator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Nwidart\Modules\Support\Stub;
use Symfony\Component\HttpFoundation\ParameterBag;

class WolfAPIServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
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

        $this->commands([
            RequestMakeCommand::class,
            CRUDMakeCommand::class,
            TransformerMakeCommand::class
        ]);

        $this->registerValidators($this->app);
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'api');

        $this->app->register(ExceptionServiceProvider::class);

        if ($this->app->runningInConsole() && $this->app->environment() === 'local') {
            $this->app->register('Laracasts\Generators\GeneratorsServiceProvider');
        }
    }

    private function registerValidators(&$app)
    {
        /**
         * Entity validator that tries to find a certain entity ID.
         *
         * Validator usage:
         *
         *      entity:{module}/{entity}
         *
         * This also accepts ids separated by commas.
         */
        Validator::extend('entity', function ($attribute, $value, $parameters, $validator) use ($app) {

            $entity = $parameters[0];

            if (strpos($entity, '/') == false) {
                throw new Exception('Format in entity validator must be: {module}/{entity}.');
            }

            [$module, $entity] = explode('/', $entity);
            $namespace = "Modules\\$module\\Entities\\$entity";

            if (!class_exists($namespace)) {
                throw new Exception(
                    "Class $namespace not found. Remember format in entity validator must be: {module}/{entity}."
                );
            }
            
            /**
             * Check if the request is passing an array, requesting to create and associate a new model.
             */
            if (($data = json_decode($value, true)) && !is_int($data)) {
                $previous = $app['request']->query;
                $app['request']->query = new ParameterBag($data);

                RequestValidator::validate($app['request'], $namespace, 'create');
                $app['request']->query = $previous;
            } else {
                foreach (explode(',', trim($value)) as $id) {
                    $instance = $namespace::find($id);
                    if (!$instance) {
                        return false;
                    }
                }
            }

            return true;
        });

        Validator::replacer('entity', function ($message, $attribute, $rule, $parameters) {
            $entity = explode('/', $parameters[0]);
            return "$entity[1] not found.";
        });
    }
}
