<?php

namespace IgnitionWolf\API\Services;

use Exception;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use IgnitionWolf\API\Http\Requests\EntityRequest;
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
     * Naming Convention: Module\{Module}\Requests\{Action}{Entities}
     *
     * Returns the filtered and validated request.
     *
     * @param string $entity The entity name.
     * @param string $action The type of request (create/update, etc). Could be a FormRequest.
     * @return FormRequest
     * @throws Exception
     */
    public static function validate(string $entity, string $action): FormRequest
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
                throw new Exception("Trying to validate $action on $entity but the FormRequest doesn't exist.");
            }
        } else {
            $formRequest = $action;
        }

        // Reflect the request and make sure it inherits the correct class
        $reflection = new ReflectionClass($formRequest);
        if (!$reflection->isSubclassOf(EntityRequest::class)) {
            throw new Exception("$formRequest must inherit EntityRequest master class.");
        }

        $request = app($formRequest);
        return $request;
    }

    /**
     * Get a list of possible form request. It fallbacks by index order.
     *
     * @param string $namespace
     * @param string $entity
     * @param string $action
     * @return array
     */
    public static function getPossibleRequests(string $namespace, string $entity, string $action): array
    {
        return [
            sprintf(
                "%s\\Http\\Requests\\%s%sRequest",
                $namespace,
                $action,
                $entity
            ),
            sprintf(
                "%s\\Http\\Requests\\%sRequest",
                $namespace,
                $action,
            ),
            sprintf(
                "%s\\Http\\Requests\\%s\\%sRequest",
                $namespace,
                $entity,
                $action
            ),
            sprintf(
                "IgnitionWolf\\API\\Http\\Requests\\%sRequest",
                $action
            ),
            sprintf(
                "IgnitionWolf\\API\\Http\\Requests\\%sEntityRequest",
                $action
            )
        ];
    }

    /**
     * Get the base namespace string.
     * @param string $class
     * @return string
     */
    public static function getNamespace(string $class): string
    {
        if (strpos($class, 'Modules\\') !== false) {
            return substr($class, 0, strpos($class, '\\', 9));
        }

        return Container::getInstance()->getNamespace();
    }
}
