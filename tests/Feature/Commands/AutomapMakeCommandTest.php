<?php

namespace IgnitionWolf\API\Tests\Feature\Commands;

use IgnitionWolf\API\Support\Stub;
use IgnitionWolf\API\Tests\TestCase;

class AutomapMakeCommandTest extends TestCase
{
    public function test_it_creates_automap_file()
    {
        $expectedDestination = $this->app->basePath('app/Automap') . '/DummyAutomap.php';

        $this->artisan('make:automap', ['name' => 'DummyAutomap']);
        $this->assertFileExists($expectedDestination);

        $this->assertStringEqualsFile(
            $expectedDestination,
            app(Stub::class)->render('automap.stub', [
                'DummyNamespace' => 'App\\Automap',
                'DummyClass' => 'DummyAutomap'
            ])
        );

        unlink($expectedDestination);
    }
}
