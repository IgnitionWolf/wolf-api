<?php

namespace IgnitionWolf\API\Tests\Feature\CRUD\Requests;

use IgnitionWolf\API\Http\Requests\UpdateEntityRequest;
use IgnitionWolf\API\Tests\DummyPoly;

class UpdateDummyRequest extends UpdateEntityRequest
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
