<?php

namespace IgnitionWolf\API\Tests\Feature\Commands;

use IgnitionWolf\API\Http\Requests\EntityRequest;
use IgnitionWolf\API\Tests\TestCase;
use IgnitionWolf\API\Support\Stub;

class RequestMakeCommandTest extends TestCase
{
    public function test_it_creates_plain_request_file()
    {
        $expectedDestination = $this->app->basePath('app/Http/Requests') . '/DummyPlainRequest.php';

        $this->artisan('make:request', ['name' => 'DummyPlainRequest']);
        $this->assertFileExists($expectedDestination);

        $this->assertStringEqualsFile(
            $expectedDestination,
            app(Stub::class)->render('request.stub', [
                'DummyNamespace' => 'App\\Http\\Requests',
                'DummyClass' => 'DummyPlainRequest'
            ])
        );

        unlink($expectedDestination);
    }

    public function test_it_creates_api_request_file()
    {
        foreach (['Create', 'Update', 'Delete', 'Read', 'List'] as $request) {
            $class = "${request}DummyRequest";
            $expectedDestination = sprintf(
                '%s/%s.php',
                $this->app->basePath('app/Http/Requests'),
                $class
            );

            $this->artisan('make:request', ['name' => $class]);
            $this->assertFileExists($expectedDestination);

            $this->assertStringEqualsFile(
                $expectedDestination,
                app(Stub::class)->render('request.api.stub', [
                    'DummyNamespace' => 'App\\Http\\Requests',
                    'DummyClass' => $class,
                    EntityRequest::class => "IgnitionWolf\\API\\Http\\Requests\\${request}EntityRequest"
                ])
            );

            unlink($expectedDestination);
        }
    }
}
