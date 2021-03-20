<?php

namespace IgnitionWolf\API\Tests\Feature\CRUD\Requests;

use IgnitionWolf\API\Http\Requests\UpdateEntityRequest;
use IgnitionWolf\API\Tests\Dummy;

class UpdateDummyRequest extends UpdateEntityRequest
{
    protected static string $model = Dummy::class;

    public static array $rules = [
        'name' => 'string'
    ];

    public function authorize(): bool
    {
        return true;
    }
}
