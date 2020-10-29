<?php

namespace IgnitionWolf\API\Services;

use Exception;
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
     * @param string $action The type of request (create/update, etc). Could be a FormRequest.
     * @return void
     * @throws Exception
     */
    public static function validate(Request &$request, string $entity, string $action): void
    {
        $formRequest = null;
        if (!class_exists($action)) {
            $explodedEntity = explode('\\', $entity);
            $options = self::getPossibleRequests(self::getNamespace($entity), end($explodedEntity), ucfirst($action));

            foreach ($options as $option) {
                $formRequest = $option;
                try {
                    if (class_exists($option)) {
                        break;
                    }
                } catch (\Exception $e) {
                    //
                }
            }

            if (!$formRequest) {
                return;
            }
        } else {
            $formRequest = $action;
        }

        // Reflect the request and make sure it inherits the correct class
        $reflection = new ReflectionClass($formRequest);
        if (!$reflection->isSubclassOf(EntityRequest::class)) {
            throw new Exception("$formRequest must inherit EntityRequest master class.");
        }

        $request = app()->make($formRequest);
    }

    /**
     * Get a list of possible form request. It fallsback by index order.
     *
     * @param string $namespace
     * @param string $entity
     * @param string $action
     * @return array
     */
    public static function getPossibleRequests(string $namespace, string $entity, string $action): array
    {
        $options = [];

        array_push($options, sprintf(
            "%s\\Http\\Requests\\%s%sRequest",
            $namespace,
            $action,
            $entity
        ));

        array_push($options, sprintf(
            "%s\\Http\\Requests\\%sRequest",
            $namespace,
            $action,
        ));

        array_push($options, sprintf(
            "%s\\Http\\Requests\\%s\\%sRequest",
            $namespace,
            $entity,
            $action
        ));

        array_push($options, sprintf(
            "IgnitionWolf\\API\\Requests\\%sRequest",
            $action
        ));

        array_push($options, sprintf(
            "IgnitionWolf\\API\\Requests\\Authentication\\%sRequest",
            $action
        ));

        array_push($options, sprintf(
            "IgnitionWolf\\API\\Requests\\%sEntityRequest",
            $action
        ));

        return $options;
    }

    /**
     * Get the base namespace string.
     * @param string $class
     * @return string
     */
    public static function getNamespace($class): string
    {
        if (strpos($class, 'Modules\\') !== false) {
            return substr($class, 0, strpos($class, '\\', 9));
        }

        return Container::getInstance()->getNamespace();
    }
}
