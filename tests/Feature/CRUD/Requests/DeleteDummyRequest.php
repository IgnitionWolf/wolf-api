<?php

namespace IgnitionWolf\API\Tests\Feature\CRUD\Requests;

use IgnitionWolf\API\Http\Requests\DeleteEntityRequest;
use IgnitionWolf\API\Tests\DummyPoly;

class DeleteDummyRequest extends DeleteEntityRequest
{
    protected static string $model = DummyPoly::class;

    public function authorize(): bool
    {
        return true;
    }
}
