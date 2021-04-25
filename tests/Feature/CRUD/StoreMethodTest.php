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
                'IgnitionWolf\API\Tests\Feature\CRUD\Requests\CreateDummyRequest'
            ]);
        });

        app(Router::class)->put('/dummy', [DummyController::class, 'store']);

        $response = $this->put('/dummy', [
            'name' => 'My Name',
            'dummy_children' => [
                [
                    'name' => 'My Child'
                ]
            ],
            'dummy_poly' => [
                [
                    'name' => 'My Poly Child'
                ]
            ]
        ]);

        $response->assertJsonFragment([
            'status' => 200,
            'success' => true,
            'data' => [
                'id' => 1,
                'name' => 'My Name',
                'dummy_children' => [
                    [
                        'id' => 1,
                        'name' => 'My Child',
                    ]
                ],
                'dummy_poly' => [
                    [
                        'id' => 1,
                        'name' => 'My Poly Child'
                    ]
                ]
            ]
        ]);
    }
}
