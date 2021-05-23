<?php

namespace IgnitionWolf\API\Tests\Feature\Commands;

use IgnitionWolf\API\Tests\TestCase;
use IgnitionWolf\API\Support\Stub;

class TransformerMakeCommandTest extends TestCase
{
    public function test_it_creates_transformer_file()
    {
        $expectedDestination = $this->app->basePath('app/Transformers') . '/DummyTransformer.php';
        $this->toBeTrashed($expectedDestination);

        $this->artisan('make:transformer', ['name' => 'DummyTransformer']);
        $this->assertFileExists($expectedDestination);

        $this->assertStringEqualsFile(
            $expectedDestination,
            app(Stub::class)->render('transformer.stub', [
                'DummyNamespace' => 'App\\Transformers',
                'DummyClass' => 'DummyTransformer'
            ])
        );

        unlink($expectedDestination);
    }
}
