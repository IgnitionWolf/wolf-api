<?php

namespace IgnitionWolf\API\Controllers;

use IgnitionWolf\API\Controllers\BaseController;
use IgnitionWolf\API\Events\EntityCreated;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use IgnitionWolf\API\Exceptions\EntityNotFoundException;
use IgnitionWolf\API\Exceptions\NotAuthorizedException;
use IgnitionWolf\API\Exceptions\ValidationException;
use IgnitionWolf\API\Requests\EntityRequest;
use Flugg\Responder\Transformers\Transformer;
use ReflectionClass;
use IgnitionWolf\API\Events\EntityUpdated;
use IgnitionWolf\API\Requests\CreateRequest;
use IgnitionWolf\API\Requests\DeleteRequest;
use IgnitionWolf\API\Requests\ReadRequest;
use IgnitionWolf\API\Requests\UpdateRequest;

abstract class EntityController extends BaseController
{
    /**
     * Points to the entity to be handled in the controller.
     *
     * @var string
     */
    protected static $entity;

    /**
     * Store a resource in the database.
     *
     * @param Request $request
     * @return void
     */
    public function create(Request $request)
    {
        $data = $this->validateRequest($request, CreateRequest::class);

        $entity = new static::$entity;

        /**
         * Fill the entity data
         */
        $data = $request->only($entity->getFillable());
        $entity->fill($data)->automap()->save();

        /**
         * Dispatch events
         */
        event(new EntityCreated($entity));

        return $this->success($entity);
    }

    /**
     * Delete a specific entity ID.
     *
     * @param Request $request
     * @param integer $id
     * @return JsonResponse
     */
    public function delete(Request $request, $id)
    {
        $this->validateRequest($request, DeleteRequest::class);

        if (!$entity = static::$entity::find($id)) {
            throw new EntityNotFoundException;
        }

        $entity->delete();

        return $this->success();
    }

    /**
     * Update a specific entity ID.
     *
     * @param Request $request
     * @param integer $id
     * @return JsonResponse
     */
    public function update(Request $request, $id)
    {
        $this->validateRequest($request, UpdateRequest::class);

        /**
         * Validate the Request
         */
        $validator = Validator::make($request->all(), $data);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        /**
         * Update the Entity
         */
        if (!$entity = static::$entity::find($id)) {
            throw new EntityNotFoundException;
        }

        $data = $request->only($entity->getFillable());
        $entity->fill($data)->automap()->save();

        /**
         * Dispatch events
         */
        event(new EntityUpdated($entity));

        return $this->success($entity);
    }

    /**
     * Get a specific entity ID.
     *
     * @param Request $request
     * @param integer $id
     * @return JsonResponse
     */
    public function get(Request $request, $id)
    {
        $this->validateRequest($request, ReadRequest::class);

        if (!$entity = static::$entity::find($id)) {
            throw new EntityNotFoundException;
        }

        return $this->success($entity);
    }

    /**
     * Check if there is a FormRequest to handle this action.
     * This only works for basic CRUD actions.
     *
     * @param string $type
     * @return void
     */
    public function validateRequest(Request &$request, string $type)
    {
        $formRequest = $type;
        if (!class_exists($formRequest)) {

            $formRequest = sprintf(
                "%s\\Requests\\%s%sRequest",
                $this->getNamespace(),
                ucfirst($type),
                get_class(new static::$entity)
            );

            if (!class_exists($formRequest)) {
                return;
            }
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
    private function getNamespace(): string
    {
        $reflection = new ReflectionClass($this);
        $namespace = $reflection->getNamespaceName();
        return str_replace('\\Http\\Controllers', '', $namespace);
    }
}
