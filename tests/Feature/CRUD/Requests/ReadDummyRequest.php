<?php

namespace IgnitionWolf\API\Tests\Feature\CRUD\Requests;

use IgnitionWolf\API\Http\Requests\ReadEntityRequest;
use IgnitionWolf\API\Tests\DummyPoly;

class ReadDummyRequest extends ReadEntityRequest
{
    protected static string $model = DummyPoly::class;

    public function authorize(): bool
    {
        return true;
    }
}
