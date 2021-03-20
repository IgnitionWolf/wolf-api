<?php

namespace IgnitionWolf\API\Tests\Feature\CRUD\Requests;

use IgnitionWolf\API\Http\Requests\CreateEntityRequest;
use IgnitionWolf\API\Tests\Dummy;

class CreateDummyRequest extends CreateEntityRequest
{
    protected static string $model = Dummy::class;

    public function authorize(): bool
    {
        return true;
    }
}
