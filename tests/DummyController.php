<?php

namespace IgnitionWolf\API\Tests;

use Exception;
use IgnitionWolf\API\Http\Controllers\CRUDController;
use Symfony\Component\HttpKernel\Exception\HttpException;

class DummyController extends CRUDController
{
    protected string $model = Dummy::class;

    protected array $allowedFilters = ['name'];

    protected array $allowedSorts = ['id'];

    public function baseException()
    {
        throw new DummyException;
    }

    public function laravelException()
    {
        throw new HttpException(statusCode: 400, message: 'DummyException');
    }

    /**
     * @throws Exception
     */
    public function phpException()
    {
        throw new Exception(message: 'DummyException', code: 400);
    }
}
