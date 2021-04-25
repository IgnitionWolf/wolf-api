<?php

namespace IgnitionWolf\API\Tests\Feature\CRUD;

use IgnitionWolf\API\Tests\Dummy;
use IgnitionWolf\API\Validator\RequestValidator;
use IgnitionWolf\API\Tests\DummyController;
use IgnitionWolf\API\Tests\TestCase;
use Illuminate\Routing\Router;

class ShowMethodTest extends TestCase
{
    public function test_it_reads_model()
    {
        $this->partialMock(RequestValidator::class, function ($mock) {
            $mock->shouldReceive('getOptions')->andReturn([
                'IgnitionWolf\API\Tests\Feature\CRUD\Requests\UpdateDummyRequest'
            ]);
        });

        Dummy::create(['name' => 'My Name']);

        app(Router::class)->get('/dummy/{id}', [DummyController::class, 'show']);

        $response = $this->get('/dummy/1');

        $response->assertJson([
            'status' => 200,
            'success' => true,
            'data' => [
                'id' => 1,
                'name' => 'My Name',
                'dummy_children' => []
            ]
        ]);
    }
}
