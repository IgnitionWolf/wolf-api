<?php

namespace IgnitionWolf\API\Controllers;

use IgnitionWolf\API\Entity\Model;
use IgnitionWolf\API\Events\EntityCreated;
use IgnitionWolf\API\Events\EntityPreCreate;
use IgnitionWolf\API\Events\EntityUpdated;

use Flugg\Responder\Http\Responses\SuccessResponseBuilder;
use IgnitionWolf\API\Strategies\Filter\FilterStrategy;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use IgnitionWolf\API\Exceptions\EntityNotFoundException;
use IgnitionWolf\API\Services\RequestValidator;
use Laravel\Scout\Searchable;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

abstract class EntityController extends BaseController
{
    /**
     * Points to the entity to be handled in the controller.
     *
     * @psalm-var class-string
     * @var Model
     */
    protected static $entity;

    /**
     * @var FilterStrategy
     */
    protected $filterStrategy;

    public function __construct(FilterStrategy $filterStrategy) {
        $this->filterStrategy = $filterStrategy;
    }

    /**
     * Create a entity.
     *
     * @param Request $request
     * @return SuccessResponseBuilder
     * @throws \Exception
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

        if (method_exists($entity, 'automap')) {
            $entity->automap();
        }

        event(new EntityPreCreate($entity, $request));

        $relationshipData = $request->only($entity->getRelationships());
        $entity->fillRelationships($relationshipData);
        $entity->save();

        event(new EntityCreated($entity, $request));

        return $this->success($entity);
    }

    /**
     * Update a specific entity ID.
     *
     * @param Request $request
     * @param integer $id
     * @return SuccessResponseBuilder
     * @throws EntityNotFoundException
     * @throws \Exception
     */
    public function update(Request $request, int $id)
    {
        RequestValidator::validate($request, static::$entity, 'update');

        /**
         * Update the Entity
         */
        if (!$entity = static::$entity::find($id)) {
            throw new EntityNotFoundException;
        }

        $data = $request->only($entity->getFillable());

        if (method_exists($entity, 'translate')) {
            $this->fillTranslatable($entity, $data);
        }

        $entity->fill($data);

        if (method_exists($entity, 'automap')) {
            $entity->automap();
        }

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
     * @throws EntityNotFoundException
     * @throws \Exception
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
     * @throws EntityNotFoundException
     * @throws \Exception
     */
    public function show(Request $request, $id)
    {
        RequestValidator::validate($request, static::$entity, 'read');

        if (!$entity = static::$entity::find($id)) {
            throw new EntityNotFoundException();
        }

        return $this->success($entity);
    }

    /**
     * List the entities.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function index(Request $request)
    {
        RequestValidator::validate($request, static::$entity, 'list');

        /**
         * Filter and sort the query
         */
        $filters = json_decode($request->get('filter', '[]'), true);
        $queryBuilder = $this->filterStrategy->filter($filters, static::$entity);

        if ($request->has('sort') && $sort = json_decode($request->get('sort'))) {
            $queryBuilder = $queryBuilder->orderBy($sort->field, $sort->order);
        }

        /**
         * Paginate and prepare the result
         */
        $paginator = $queryBuilder->paginate((int) $request->get('limit', 10));
        $adapter = new IlluminatePaginatorAdapter($paginator);

        $collection = $paginator->getCollection();

        return responder()->success($collection)->paginator($adapter)->respond();
    }

    /**
     * Due to a bug in Spatie's package, we need to make sure translations
     * are being handled correctly.
     *
     * @link https://github.com/spatie/laravel-translatable/issues/225
     * @param Model $entity
     * @param array $data
     * @return void
     */
    private function fillTranslatable(Model $entity, array &$data)
    {
        foreach ($entity->getTranslatableAttributes() as $attribute) {
            if (!isset($data[$attribute]) || empty($data[$attribute])) {
                continue;
            }

            // Make sure the translatable attribute changed, then unset and assign it again.
            if ($entity->$attribute !== $data[$attribute]) {
                unset($entity->$attribute);
                $entity->setTranslations($attribute, $data[$attribute]);
                unset($data[$attribute]);
            }
        }
    }
}
