<?php

namespace IgnitionWolf\API\Tests\Feature\CRUD;

use IgnitionWolf\API\Tests\Dummy;
use IgnitionWolf\API\Validator\RequestValidator;
use IgnitionWolf\API\Tests\DummyController;
use IgnitionWolf\API\Tests\TestCase;
use Illuminate\Routing\Router;

class DeleteMethodTest extends TestCase
{
    public function test_it_deletes_model()
    {
        $this->partialMock(RequestValidator::class, function ($mock) {
            $mock->shouldReceive('getOptions')->andReturn([
                'IgnitionWolf\API\Tests\Feature\CRUD\Requests\UpdateDummyRequest'
            ]);
        });

        Dummy::create();

        app(Router::class)->delete('/dummy/{id}', [DummyController::class, 'destroy']);

        $response = $this->delete('/dummy/1');

        $response->assertJsonFragment([
            'status' => 200,
            'success' => true
        ]);

        $this->assertEmpty(Dummy::find(1));
    }
}
