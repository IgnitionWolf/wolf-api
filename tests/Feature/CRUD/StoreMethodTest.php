<?php

namespace IgnitionWolf\API\Tests\Feature\CRUD;

use IgnitionWolf\API\Validator\RequestValidator;
use IgnitionWolf\API\Tests\DummyController;
use IgnitionWolf\API\Tests\TestCase;
use Illuminate\Routing\Router;

class StoreMethodTest extends TestCase
{
    public function test_it_stores_model()
    {
        $this->partialMock(RequestValidator::class, function ($mock) {
            $mock->shouldReceive('getOptions')->andReturn([
                'IgnitionWolf\API\Tests\Feature\CRUD\Requests\UpdateDummyRequest'
            ]);
        });

        app(Router::class)->put('/dummy', [DummyController::class, 'store']);

        $response = $this->put('/dummy');

        $response->assertJsonFragment([
            'status' => 200,
            'success' => true,
            'data' => [
                'id' => 1,
                'name' => null
            ]
        ]);
    }
}
