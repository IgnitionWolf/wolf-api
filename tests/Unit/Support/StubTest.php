<?php

namespace IgnitionWolf\API\Tests\Unit\Support;

use IgnitionWolf\API\Support\Stub;
use IgnitionWolf\API\Tests\TestCase;
use Exception;

class StubTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function test_it_renders_file()
    {
        $content = app(Stub::class)->render(
            __DIR__ . '/dummy.stub',
            [
                'DummyNamespace' => 'App',
                'DummyClassname' => 'Dummy'
            ]
        );

        $this->assertStringContainsString("namespace App;", $content);
        $this->assertStringContainsString("class Dummy", $content);
    }
}
