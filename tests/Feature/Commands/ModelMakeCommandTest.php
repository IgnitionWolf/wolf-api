<?php

namespace IgnitionWolf\API\Tests\Feature\Commands;

use IgnitionWolf\API\Tests\TestCase;
use IgnitionWolf\API\Support\Stub;

class ModelMakeCommandTest extends TestCase
{
    public function test_it_creates_model_file()
    {
        $expectedDestination = $this->app->basePath('app/Models') . '/Dummy.php';
        $this->toBeTrashed($expectedDestination);

        $this->artisan('make:model', ['name' => 'Dummy']);
        $this->assertFileExists($expectedDestination);

        $this->assertStringEqualsFile(
            $expectedDestination,
            app(Stub::class)->render('model.stub', [
                'DummyNamespace' => 'App\\Models',
                'DummyClass' => 'Dummy'
            ])
        );

        unlink($expectedDestination);
    }
}
