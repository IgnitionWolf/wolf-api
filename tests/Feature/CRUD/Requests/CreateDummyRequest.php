<?php

namespace IgnitionWolf\API\Tests\Feature\CRUD\Requests;

use IgnitionWolf\API\Http\Requests\CreateEntityRequest;
use IgnitionWolf\API\Tests\DummyPoly;

class CreateDummyRequest extends CreateEntityRequest
{
    protected static string $model = DummyPoly::class;

    public static array $rules = [
        'name' => 'string',
        'dummy_children.*.name' => 'string',
        'dummy_poly.*.name' => 'string'
    ];

    public function authorize(): bool
    {
        return true;
    }
}
