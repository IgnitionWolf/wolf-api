<?php

namespace IgnitionWolf\API\Tests\Feature\CRUD\Requests;

use IgnitionWolf\API\Http\Requests\ListEntityRequest;
use IgnitionWolf\API\Tests\DummyPoly;

class ListDummyRequest extends ListEntityRequest
{
    protected static string $model = DummyPoly::class;

    public function authorize(): bool
    {
        return true;
    }
}
