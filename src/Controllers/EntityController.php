<?php

namespace IgnitionWolf\API\Controllers;

use IgnitionWolf\API\Controllers\BaseController;
use IgnitionWolf\API\Entity\Model;
use IgnitionWolf\API\Events\EntityCreated;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use IgnitionWolf\API\Exceptions\EntityNotFoundException;
use IgnitionWolf\API\Requests\EntityRequest;
use ReflectionClass;
use IgnitionWolf\API\Events\EntityUpdated;

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
    public function store(Request $request)
    {
        $data = $this->validateRequest($request, 'create');

        /**
         * @var Model
         */
        $entity = new static::$entity;

        /**
         * Fill the entity data
         */
        $data = $request->only($entity->getFillable());
        $entity->fill($data);
        $entity->automap();
        $entity->save();

        $relationshipData = $request->only($entity->getRelationships());
        $entity->fillRelationships($relationshipData);
        $entity->save();

        /**
         * Dispatch events
         */
        event(new EntityCreated($entity));

        return $this->success($entity);
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
        $this->validateRequest($request, 'update');

        /**
         * Update the Entity
         */
        if (!$entity = static::$entity::find($id)) {
            throw new EntityNotFoundException;
        }

        $data = $request->only($entity->getFillable());
        $entity->fill($data);
        $entity->automap();
        
        $relationshipData = $request->only($entity->getRelationships());
        $entity->fillRelationships($relationshipData);
        $entity->save();

        /**
         * Dispatch events
         */
        event(new EntityUpdated($entity));

        return $this->success($entity);
    }

    /**
     * Delete a specific entity ID.
     *
     * @param Request $request
     * @param integer $id
     * @return JsonResponse
     */
    public function destroy(Request $request, $id)
    {
        $this->validateRequest($request, 'delete');

        if (!$entity = static::$entity::find($id)) {
            throw new EntityNotFoundException;
        }

        $entity->delete();

        return $this->success();
    }

    /**
     * Get a specific entity ID.
     *
     * @param Request $request
     * @param integer $id
     * @return JsonResponse
     */
    public function show(Request $request, $id)
    {
        $this->validateRequest($request, 'read');

        if (!$entity = static::$entity::find($id)) {
            throw new EntityNotFoundException;
        }

        return $this->success($entity);
    }

    /**
     * Check if there is a FormRequest to handle this action.
     * This only works for basic CRUD actions.
     *
     * Naming Convention: Namespace\Requests\{Action}{Model}
     *
     * @param string $type
     * @return void
     */
    public function validateRequest(Request &$request, string $type)
    {
        $formRequest = null;
        if (!class_exists($type)) {
            $explodedEntity = explode('\\', static::$entity);
            $formRequest = sprintf(
                "%s\\Http\\Requests\\%s%sRequest",
                $this->getNamespace(),
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
    private function getNamespace(): string
    {
        $reflection = new ReflectionClass($this);
        $namespace = $reflection->getNamespaceName();
        return str_replace('\\Http\\Controllers', '', $namespace);
    }
}
