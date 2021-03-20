<?php

namespace IgnitionWolf\API\Tests\Feature\CRUD\Requests;

use IgnitionWolf\API\Http\Requests\DeleteEntityRequest;
use IgnitionWolf\API\Tests\Dummy;

class DeleteDummyRequest extends DeleteEntityRequest
{
    protected static string $model = Dummy::class;

    public function authorize(): bool
    {
        return true;
    }
}
