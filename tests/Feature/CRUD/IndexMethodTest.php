<?php

namespace IgnitionWolf\API\Tests\Feature\CRUD;

use IgnitionWolf\API\Tests\Dummy;
use IgnitionWolf\API\Validator\RequestValidator;
use IgnitionWolf\API\Tests\DummyController;
use IgnitionWolf\API\Tests\TestCase;
use Illuminate\Routing\Router;

class IndexMethodTest extends TestCase
{
    public function test_it_lists_model_in_asc()
    {
        $this->partialMock(RequestValidator::class, function ($mock) {
            $mock->shouldReceive('getOptions')->andReturn([
                'IgnitionWolf\API\Tests\Feature\CRUD\Requests\ListDummyRequest'
            ]);
        });

        Dummy::create(['name' => 'First']);
        Dummy::create(['name' => 'Second']);

        app(Router::class)->get('/dummy', [DummyController::class, 'index']);

        $response = $this->get('/dummy');

        $response->assertJsonFragment([
            'status' => 200,
            'success' => true,
            'data' => [
                [ 'id' => 1, 'name' => 'First', 'dummy_children' => [], 'dummy_poly' => [] ],
                [ 'id' => 2, 'name' => 'Second', 'dummy_children' => [], 'dummy_poly' => [] ],
            ]
        ]);
    }

    public function test_it_lists_model_in_desc()
    {
        $this->partialMock(RequestValidator::class, function ($mock) {
            $mock->shouldReceive('getOptions')->andReturn([
                'IgnitionWolf\API\Tests\Feature\CRUD\Requests\ListDummyRequest'
            ]);
        });

        Dummy::create(['name' => 'First']);
        Dummy::create(['name' => 'Second']);

        app(Router::class)->get('/dummy', [DummyController::class, 'index']);

        $response = $this->get('/dummy?sort=-id');

        $response->assertJsonFragment([
            'status' => 200,
            'success' => true,
            'data' => [
                [ 'id' => 2, 'name' => 'Second', 'dummy_children' => [], 'dummy_poly' => [] ],
                [ 'id' => 1, 'name' => 'First', 'dummy_children' => [], 'dummy_poly' => [] ],
            ]
        ]);

        $response->assertJsonPath('data.0.id', 2);
    }

    public function test_it_lists_model_with_filter()
    {
        $this->partialMock(RequestValidator::class, function ($mock) {
            $mock->shouldReceive('getOptions')->andReturn([
                'IgnitionWolf\API\Tests\Feature\CRUD\Requests\ListDummyRequest'
            ]);
        });

        Dummy::create(['name' => 'First']);
        Dummy::create(['name' => 'Second']);

        app(Router::class)->get('/dummy', [DummyController::class, 'index']);

        $response = $this->get('/dummy?filter[name]=Second');

        $response->assertJsonFragment([
            'status' => 200,
            'success' => true,
            'data' => [
                [ 'id' => 2, 'name' => 'Second', 'dummy_children' => [], 'dummy_poly' => [] ],
            ]
        ]);
    }
}
