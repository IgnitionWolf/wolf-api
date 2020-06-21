<?php

namespace IgnitionWolf\API\Services;

use Illuminate\Http\Request;
use IgnitionWolf\API\Requests\EntityRequest;
use Illuminate\Container\Container;
use ReflectionClass;

/**
 * Static helper class to validate entity requests.
 */
class RequestValidator
{
    /**
     * Check if there is a FormRequest to handle this action.
     * This only works for basic CRUD actions.
     *
     * Naming Convention: Module\{Module}\Requests\{Action}{Model}
     *
     * @param Request $request The request to validate.
     * @param string $entity The entity name.
     * @param string $type The type of request (create/update, etc).
     * @return void
     */
    public static function validate(Request &$request, string $entity, string $type): void
    {
        $formRequest = null;
        if (!class_exists($type)) {
            $explodedEntity = explode('\\', $entity);
            $formRequest = sprintf(
                "%s\\Http\\Requests\\%s%sRequest",
                self::getNamespace($entity),
                ucfirst($type),
                end($explodedEntity)
            );

            if (!class_exists($formRequest)) {
                $formRequest = sprintf(
                    "IgnitionWolf\\API\\Requests\\%sRequest",
                    ucfirst($type)
                );
            }
        } else {
            $formRequest = $type;
        }

        // Reflect the request and make sure it inherits the correct class
        $reflection = new ReflectionClass($formRequest);
        if (!$reflection->isSubclassOf(EntityRequest::class)) {
            throw new \Exception("$formRequest must inherit EntityRequest master class.");
        }

        $request = app()->make($formRequest);
    }

    /**
     * Get the base namespace string.
     * @return string
     */
    private static function getNamespace($class): string
    {
        if (strpos($class, 'Modules\\') !== false) {
            return substr($class, 0, strpos($class, '\\', 9));
        }

        return Container::getInstance()->getNamespace();
    }
}
