<?php

namespace IgnitionWolf\API\Controllers;

use IgnitionWolf\API\Controllers\BaseController;
use IgnitionWolf\API\Entity\Model;
use IgnitionWolf\API\Events\EntityCreated;
use Flugg\Responder\Http\Responses\SuccessResponseBuilder;
use Illuminate\Http\Request;

use IgnitionWolf\API\Exceptions\EntityNotFoundException;
use IgnitionWolf\API\Events\EntityUpdated;
use IgnitionWolf\API\Services\RequestValidator;

abstract class EntityController extends BaseController
{
    /**
     * Points to the entity to be handled in the controller.
     *
     * @psalm-var class-string
     * @var string
     */
    protected static $entity;

    /**
     * Create a entity.
     *
     * @param Request $request
     * @return SuccessResponseBuilder
     */
    public function store(Request $request): SuccessResponseBuilder
    {
        RequestValidator::validate($request, static::$entity, 'create');

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
     * @return SuccessResponseBuilder
     */
    public function update(Request $request, $id)
    {
        RequestValidator::validate($request, static::$entity, 'update');

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
     * @return SuccessResponseBuilder
     */
    public function destroy(Request $request, $id)
    {
        RequestValidator::validate($request, static::$entity, 'delete');

        if (!$entity = static::$entity::find($id)) {
            throw new EntityNotFoundException;
        }

        $entity->delete();

        return $this->success();
    }

    /**
     * Read a specific entity ID.
     *
     * @param Request $request
     * @param integer $id
     * @return SuccessResponseBuilder
     */
    public function show(Request $request, $id)
    {
        RequestValidator::validate($request, static::$entity, 'read');

        if (!$entity = static::$entity::find($id)) {
            throw new EntityNotFoundException;
        }

        return $this->success($entity);
    }

    /**
     * List the entities.
     *
     * @param Request $request
     * @return SuccessResponseBuilder
     */
    public function index(Request $request)
    {
        return $this->success([]);
    }
}
