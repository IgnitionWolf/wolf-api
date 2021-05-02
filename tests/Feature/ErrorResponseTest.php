<?php

namespace IgnitionWolf\API\Tests\Feature;

use IgnitionWolf\API\Tests\TestCase;
use Illuminate\Routing\Router;

class ErrorResponseTest extends TestCase
{
    public function test_it_returns_proper_error_response()
    {
        app(Router::class)->get('/error/test', function () {
            throw new \Exception('Unexpected error');
        });

        config(['app.debug' => false]);

        $response = $this->get('/error/test');

        $response->assertExactJson([
            'status' => 500,
            'success' => false,
            'error' => [
                'code' => 'INTERNAL_ERROR',
                'message' => 'Unexpected error'
            ]
        ]);
    }
}
