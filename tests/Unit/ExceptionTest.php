<?php

namespace IgnitionWolf\API\Tests\Unit;

use IgnitionWolf\API\Tests\DummyController;
use IgnitionWolf\API\Tests\TestCase;
use Illuminate\Routing\Router;

class ExceptionTest extends TestCase
{
    public function test_it_renders_base_exception()
    {
        app(Router::class)->get('/exception', [DummyController::class, 'baseException']);

        $response = $this->get('/exception');

        $response->assertJsonFragment([
            'status' => 400,
            'success' => false,
            'error' => [
                'code' => 'DUMMY_EXCEPTION',
                'message' => 'DummyException',
                'errors' => ['key' => 'value'],
            ]
        ]);
    }

    public function test_it_renders_laravel_exception()
    {
        app(Router::class)->get('/exception', [DummyController::class, 'laravelException']);

        $response = $this->get('/exception');

        $response->assertJsonFragment([
            'status' => 400,
            'success' => false,
            'error' => [
                'code' => 'INTERNAL_ERROR',
                'message' => 'DummyException',
            ]
        ]);
    }

    public function test_it_renders_php_exception()
    {
        app(Router::class)->get('/exception', [DummyController::class, 'phpException']);

        $response = $this->get('/exception');

        $response->assertJsonFragment([
            'status' => 400,
            'success' => false,
            'error' => [
                'code' => 'INTERNAL_ERROR',
                'message' => 'DummyException',
            ]
        ]);
    }
}
