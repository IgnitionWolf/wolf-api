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

        $dummy = Dummy::create([
            'name' => 'Old Name',
            'dummy_children' => [
                ['name' => 'Old Child']
            ],
            'dummy_poly' => [
                ['name' => 'Old Poly Child']
            ],
            'dummy_child' => [
                'name' => 'Old Child'
            ]
        ]);

        app(Router::class)->post('/dummy/{id}', [DummyController::class, 'update']);

        $response = $this->post('/dummy/1', [
            'name' => 'New Name',
            'dummy_child' => [
                'name' => 'New Child'
            ],
            'dummy_children' => [
                [
                    'name' => 'New Child'
                ]
            ],
            'dummy_poly' => [
                [
                    'name' => 'New Poly Child'
                ]
            ],
        ]);

        $response->assertJsonFragment([
            'status' => 200,
            'success' => true,
            'data' => [
                'id' => 1,
                'name' => 'New Name',
                'dummy_child' => [
                    'id' => 3,
                    'name' => 'New Child'
                ],
                'dummy_children' => [
                    [
                        'id' => 4,
                        'name' => 'New Child'
                    ]
                ],
                'dummy_poly' => [
                    [
                        'id' => 2,
                        'name' => 'New Poly Child'
                    ]
                ],
            ]
        ]);
    }
}
