<?php

namespace IgnitionWolf\API\Tests;

use IgnitionWolf\API\Http\Controllers\CRUDController;

class DummyController extends CRUDController
{
    protected string $model = Dummy::class;

    protected array $allowedFilters = ['name'];

    protected array $allowedSorts = ['id'];
}
