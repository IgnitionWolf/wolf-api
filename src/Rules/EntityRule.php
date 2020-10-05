<?php

namespace IgnitionWolf\API\Rules;

use Exception;
use IgnitionWolf\API\Services\RequestValidator;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\ParameterBag;
use Illuminate\Contracts\Foundation\Application;

/**
 * Entity validator that tries to find a certain entity ID.
 *
 * Validator usage:
 *
 *      entity:{module}/{entity}
 *
 * This accepts a single ID as value, or multiple IDs separated by commas.
 */
class EntityRule implements Rule
{
    public function __invoke(Application &$app): void
    {
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
