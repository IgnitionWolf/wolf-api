<?php

namespace IgnitionWolf\API\Tests\Feature\Commands;

use IgnitionWolf\API\Tests\TestCase;
use IgnitionWolf\API\Support\Stub;

class ControllerMakeCommandTest extends TestCase
{
    public function tearDown(): void
    {
        if (file_exists($path = $this->app->basePath('app/Models/Parent.php'))) {
            unlink($path);
        }

        if (file_exists($path = $this->app->basePath('app/Models/Dummy.php'))) {
            unlink($path);
        }

        parent::tearDown();
    }

    public function test_it_creates_plain_controller_file()
    {
        $expectedDestination = $this->app->basePath('app/Http/Controllers/DummyPlainController.php');

        $this->artisan('make:controller', ['name' => 'DummyPlainController']);
        $this->assertFileExists($expectedDestination);

        $this->assertStringEqualsFile(
            $expectedDestination,
            app(Stub::class)->render('controller.plain.stub', [
                'DummyNamespace' => 'App\\Http\\Controllers',
                'DummyClass' => 'DummyPlainController'
            ])
        );

        unlink($expectedDestination);
    }

    public function test_it_creates_api_controller_file()
    {
        $expectedDestination = $this->app->basePath('app/Http/Controllers/DummyApiController.php');

        $this->artisan('make:controller', ['name' => 'DummyApiController', '--api' => 'true']);
        $this->assertFileExists($expectedDestination);

        $this->assertStringEqualsFile(
            $expectedDestination,
            app(Stub::class)->render('controller.api.stub', [
                'DummyNamespace' => 'App\\Http\\Controllers',
                'DummyClass' => 'DummyApiController'
            ])
        );

        unlink($expectedDestination);
    }

    public function test_it_creates_model_controller_file()
    {
        $expectedDestination = $this->app->basePath('app/Http/Controllers/DummyModelController.php');

        $this->artisan('make:controller', ['name' => 'DummyModelController', '--model' => 'Dummy'])
            ->expectsQuestion('A App\Models\Dummy model does not exist. Do you want to generate it?', 'yes')
            ->assertExitCode(0);

        $this->assertFileExists($expectedDestination);

        $this->assertStringEqualsFile(
            $expectedDestination,
            app(Stub::class)->render('controller.model.stub', [
                'DummyNamespace' => 'App\\Http\\Controllers',
                'DummyFullModelClass' => 'App\\Models\\Dummy',
                'DummyClass' => 'DummyModelController'
            ])
        );

        unlink($this->app->basePath('app/Models/Dummy.php'));
        unlink($expectedDestination);
    }

    public function test_it_creates_invokable_controller_file()
    {
        $expectedDestination = $this->app->basePath('app/Http/Controllers/DummyInvokableController.php');

        $this->artisan('make:controller', ['name' => 'DummyInvokableController', '--invokable' => 'true']);
        $this->assertFileExists($expectedDestination);

        $this->assertStringEqualsFile(
            $expectedDestination,
            app(Stub::class)->render('controller.invokable.stub', [
                'DummyNamespace' => 'App\\Http\\Controllers',
                'DummyClass' => 'DummyInvokableController'
            ])
        );

        unlink($expectedDestination);
    }
}
