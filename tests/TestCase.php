<?php

namespace IgnitionWolf\API\Tests;

use Flugg\Responder\ResponderServiceProvider;
use IgnitionWolf\API\ExceptionServiceProvider;
use IgnitionWolf\API\Concerns\HasGarbageCollection;
use IgnitionWolf\API\WolfAPIServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\QueryBuilder\QueryBuilderServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    use RefreshDatabase, HasGarbageCollection;

    public static array $config = [];

    public function setUp(): void
    {
        parent::setUp();

        if (!self::$config) {
            self::$config = require 'config/config.php';
            $this->artisan('clear-compiled');
        }

        $this->loadMigrationsFrom(__DIR__ . '/Migrations');
    }

    public function tearDown(): void
    {
        parent::tearDown();

        $this->collect();
    }

    protected function getPackageProviders($app): array
    {
        return [
            WolfAPIServiceProvider::class,
            ExceptionServiceProvider::class,
            ResponderServiceProvider::class,
            QueryBuilderServiceProvider::class
        ];
    }
}
