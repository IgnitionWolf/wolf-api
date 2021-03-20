<?php

namespace IgnitionWolf\API\Tests\Feature\CRUD;

use IgnitionWolf\API\Tests\Dummy;
use IgnitionWolf\API\Validator\RequestValidator;
use IgnitionWolf\API\Tests\DummyController;
use IgnitionWolf\API\Tests\TestCase;
use Illuminate\Routing\Router;

class UpdateMethodTest extends TestCase
{
    public function test_it_updates_model()
    {
        $this->partialMock(RequestValidator::class, function ($mock) {
            $mock->shouldReceive('getOptions')->andReturn([
                'IgnitionWolf\API\Tests\Feature\CRUD\Requests\UpdateDummyRequest'
            ]);
        });

        Dummy::create(['name' => 'Old Name']);

        app(Router::class)->post('/dummy/{id}', [DummyController::class, 'update']);

        $response = $this->post('/dummy/1', [
            'name' => 'New Name'
        ]);

        $response->assertJsonFragment([
            'status' => 200,
            'success' => true,
            'data' => [
                'id' => 1,
                'name' => 'New Name'
            ]
        ]);
    }
}
