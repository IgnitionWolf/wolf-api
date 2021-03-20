<?php

namespace IgnitionWolf\API\Http\Controllers;

use IgnitionWolf\API\Concerns\FillsTranslatable;
use IgnitionWolf\API\Concerns\WithHooks;
use IgnitionWolf\API\Concerns\FillsDataFromRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

use IgnitionWolf\API\Exceptions\EntityNotFoundException;
use IgnitionWolf\API\Validator\RequestValidator;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use Spatie\QueryBuilder\QueryBuilder;

use Exception;

class CRUDController extends BaseController
{
    use WithHooks, FillsDataFromRequest, FillsTranslatable;

    /**
     * Points to the model to be handled in the controller.
     *
     * @var string
     */
    protected string $model;

    /**
     * List of sortable attributes for the list endpoint.
     *
     * @var array
     */
    protected array $allowedSorts = [];

    /**
     * List of filterable attributes for the list endpoint.
     *
     * @var array
     */
    protected array $allowedFilters = [];

    /**
     * Create an entity.
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function store(): JsonResponse
    {
        $request = $this->validate('create');

        $entity = new $this->model;
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

        if (!$entity = app($this->model)->find($id)) {
            throw new EntityNotFoundException;
        }

        $this->fillFromRequest($request, $entity);

        $this->onPreUpdate($request, $entity);
        $entity->save();
        $this->onPostUpdate($request, $entity);

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

        $model = app($this->model);
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

        if (!$entity = app($this->model)->find($id)) {
            throw new EntityNotFoundException;
        }

        return $this->success($entity);
    }

    /**
     * List the entities, this uses Spatie's query builder to prepare the result.
     *
     * @url https://github.com/spatie/laravel-query-builder
     * @return JsonResponse
     * @throws Exception
     */
    public function index(): JsonResponse
    {
        $request = $this->validate('list');

        $builder = QueryBuilder::for($this->model)
            ->allowedFilters($this->allowedFilters)
            ->allowedSorts($this->allowedSorts);

        $limit = (int) $request->input('limit', 10);
        $paginator = $builder->paginate($limit);
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
        return app(RequestValidator::class)->validate($this->model, $action);
    }
}
