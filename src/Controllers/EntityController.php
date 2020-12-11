<?php

namespace IgnitionWolf\API\Controllers;

use IgnitionWolf\API\Entity\Model;
use Exception;

use IgnitionWolf\API\Strategies\Filter\FilterStrategy;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use IgnitionWolf\API\Exceptions\EntityNotFoundException;
use IgnitionWolf\API\Services\EntityRequestValidator;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

abstract class EntityController extends BaseController
{
    use WithHooks;

    /**
     * Points to the entity to be handled in the controller.
     *
     * @psalm-var class-string
     * @var string
     */
    protected static string $entity;

    /**
     * Create a entity.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function store(Request $request): JsonResponse
    {
        $request = EntityRequestValidator::validate($request, static::$entity, 'create');
        $validAttributes = $request->validated();

        $entity = new static::$entity;

        $entity->fill($validAttributes);

        if (method_exists($entity, 'automap')) {
            $entity->automap();
        }

        $entity->fillRelationships($request->only(
            array_intersect($entity->getRelationships(), array_keys($validAttributes))
        ));

        $this->onPreCreate($request, $entity);
        $entity->save();
        $this->onPostCreate($request, $entity);

        return $this->success($entity);
    }

    /**
     * Update a specific entity ID.
     *
     * @param Request $request
     * @param integer $id
     * @return JsonResponse
     * @throws EntityNotFoundException
     * @throws Exception
     */
    public function update(Request $request, int $id)
    {
        EntityRequestValidator::validate($request, static::$entity, 'update');
        $validAttributes = $request->validated();

        $model = app()->make(static::$entity);
        if (!$entity = $model->find($id)) {
            throw new EntityNotFoundException;
        }

        $data = $entity->fill($validAttributes);

        if (method_exists($entity, 'translate')) {
            $this->fillTranslatable($entity, $data);
        }

        $entity->fill($data);

        if (method_exists($entity, 'automap')) {
            $entity->automap();
        }

        $entity->fillRelationships($request->only(
            array_intersect($entity->getRelationships(), array_keys($validAttributes))
        ));
        $entity->save();

        return $this->success($entity);
    }

    /**
     * Delete a specific entity ID.
     *
     * @param Request $request
     * @param integer $id
     * @return JsonResponse
     * @throws EntityNotFoundException
     * @throws Exception
     */
    public function destroy(Request $request, $id)
    {
        EntityRequestValidator::validate($request, static::$entity, 'delete');

        $model = app()->make(static::$entity);
        if (!$entity = $model->find($id)) {
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
     * @return JsonResponse
     * @throws EntityNotFoundException
     * @throws Exception
     */
    public function show(Request $request, int $id)
    {
        EntityRequestValidator::validate($request, static::$entity, 'read');

        $model = app()->make(static::$entity);
        if (!$entity = $model->find($id)) {
            throw new EntityNotFoundException();
        }

        return $this->success($entity);
    }

    /**
     * List the entities.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function index(Request $request)
    {
        EntityRequestValidator::validate($request, static::$entity, 'list');

        /**
         * Filter and sort the query
         */
        $filters = json_decode($request->get('filter', '[]'), true);

        $queryBuilder = app()->make(FilterStrategy::class)->filter($filters, static::$entity);

        if ($request->has('sort') && $sort = json_decode($request->get('sort'))) {
            $queryBuilder = is_string($queryBuilder)
                ? $queryBuilder::orderBy($sort->field, $sort->order)
                : $queryBuilder->orderBy($sort->field, $sort->order);
        }

        /**
         * Paginate and prepare the result
         */
        $limit = (int) $request->get('limit', 10);
        $paginator = is_string($queryBuilder) ? $queryBuilder::paginate($limit) : $queryBuilder->paginate($limit);
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
     * @throws Exception
     */
    private function fillTranslatable(Model $entity, array &$data)
    {
        foreach ($entity->getTranslatableAttributes() as $attribute) {
            if (!isset($data[$attribute]) || empty($data[$attribute])) {
                continue;
            }

            // Make sure the translatable attribute changed, then unset and assign it again.
            if ($entity->$attribute !== $data[$attribute]) {
                if (is_string($data[$attribute])) {
                    throw new Exception('You should pass an array to translatable fields.', 400);
                }

                unset($entity->$attribute);
                $entity->setTranslations($attribute, $data[$attribute]);
                unset($data[$attribute]);
            }
        }
    }
}
