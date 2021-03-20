<?php

namespace IgnitionWolf\API\Tests;

use Flugg\Responder\ResponderServiceProvider;
use IgnitionWolf\API\ExceptionServiceProvider;
use IgnitionWolf\API\WolfAPIServiceProvider;
use Spatie\QueryBuilder\QueryBuilderServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    public static array $config = [];

    public function setUp(): void
    {
        parent::setUp();

        if (!self::$config) {
            self::$config = require 'config/config.php';
        }

        $this->loadMigrationsFrom(__DIR__ . '/Migrations');
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
