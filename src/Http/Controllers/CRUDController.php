<?php

namespace IgnitionWolf\API\Http\Controllers;

use IgnitionWolf\API\Entity\Model;
use Exception;

use IgnitionWolf\API\Strategies\Filter\FilterStrategy;
use IgnitionWolf\API\Traits\FillsDataFromRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use IgnitionWolf\API\Exceptions\EntityNotFoundException;
use IgnitionWolf\API\Services\EntityRequestValidator;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

abstract class CRUDController extends BaseController
{
    use WithHooks, FillsDataFromRequest;

    /**
     * Points to the model to be handled in the controller.
     *
     * @psalm-var class-string
     * @var string
     */
    protected static string $model;

    /**
     * Create a entity.
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function store(): JsonResponse
    {
        $request = $this->validate('create');

        $entity = new static::$model;
        $this->fillFromRequest($request, $entity);

        $this->onPreCreate($request, $entity);
        $entity->save();
        $this->onPostCreate($request, $entity);

        return $this->success($entity);
    }

    /**
     * Update a specific entity ID.
     *
     * @param integer|string $id
     * @return JsonResponse
     * @throws EntityNotFoundException
     * @throws Exception
     */
    public function update($id): JsonResponse
    {
        $request = $this->validate('update');

        $model = app()->make(static::$model);
        if (!$entity = $model->find($id)) {
            throw new EntityNotFoundException;
        }

        $this->fillFromRequest($request, $entity);
        $entity->save();
        return $this->success($entity);
    }

    /**
     * Delete a specific entity ID.
     *
     * @param integer|string $id
     * @return JsonResponse
     * @throws EntityNotFoundException
     * @throws Exception
     */
    public function destroy($id): JsonResponse
    {
        $this->validate('delete');

        $model = app()->make(static::$model);
        if (!$entity = $model->find($id)) {
            throw new EntityNotFoundException;
        }

        $entity->delete();
        return $this->success();
    }

    /**
     * Read a specific entity ID.
     *
     * @param integer|string $id
     * @return JsonResponse
     * @throws EntityNotFoundException
     * @throws Exception
     */
    public function show($id): JsonResponse
    {
        $this->validate('read');

        $model = app()->make(static::$model);
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
    public function index(Request $request): JsonResponse
    {
        $this->validate('list');

        /**
         * Filter and sort the query
         */
        $filters = json_decode($request->get('filter', '[]'), true);

        $queryBuilder = app()->make(FilterStrategy::class)->filter($filters, static::$model);

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
     * Wrapper function to validate a CRUD request.
     *
     * @param string $action
     * @return FormRequest
     * @throws Exception
     */
    private function validate(string $action): FormRequest
    {
        return app()->make(EntityRequestValidator::class)->validate(request(), static::$model, $action);
    }
}
