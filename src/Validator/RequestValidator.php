<?php

namespace IgnitionWolf\API\Validator;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Container\Container;
use Illuminate\Http\Request;

class RequestValidator
{
    /**
     * Check if there is a FormRequest to handle a specific action (usually CRUD).
     *
     * This searches in the following namespaces:
     * - App\Http\Requests\{Model}\{Action}Request
     * - App\Http\Requests\{Action}{Model}Request
     *
     * Or defaults to WolfAPI's EntityRequest.
     *
     * @param string $entity the entity class namespace.
     * @param string $action request type (e.g. create, update, delete), or form request class namespace.
     * @return Request|FormRequest
     */
    public function validate(string $entity, string $action)
    {
        if (class_exists($action)) {
            return app($action);
        }

        $explodedEntity = explode('\\', $entity);
        $options = $this->getOptions($this->getNamespace($entity), end($explodedEntity), ucfirst($action));

        foreach ($options as $option) {
            if (class_exists($option)) {
                return app($option);
            }
        }

        return request();
    }

    /**
     * Get a list of possible form request.
     *
     * @param string $namespace
     * @param string $entity
     * @param string $action
     * @return array
     */
    public function getOptions(string $namespace, string $entity, string $action): array
    {
        return [
            sprintf("%s\\Http\\Requests\\%s%sRequest", $namespace, $action, $entity),
            sprintf("%s\\Http\\Requests\\%s\\%sRequest", $namespace, $entity, $action),
            'IgnitionWolf\\API\\Http\\Requests\\EntityRequest'
        ];
    }

    /**
     * Get the base namespace string.
     * @param string $class
     * @return string
     */
    protected function getNamespace(string $class): string
    {
        return Container::getInstance()->getNamespace();
    }
}
