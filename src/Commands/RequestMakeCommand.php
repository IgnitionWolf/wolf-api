<?php

namespace IgnitionWolf\API\Commands;

use IgnitionWolf\API\Http\Requests\CreateEntityRequest;
use IgnitionWolf\API\Http\Requests\DeleteEntityRequest;
use IgnitionWolf\API\Http\Requests\EntityRequest;
use IgnitionWolf\API\Http\Requests\ListEntityRequest;
use IgnitionWolf\API\Http\Requests\ReadEntityRequest;
use IgnitionWolf\API\Http\Requests\UpdateEntityRequest;

class RequestMakeCommand extends \Illuminate\Foundation\Console\RequestMakeCommand
{
    protected static array $requests = [
        'create' => CreateEntityRequest::class,
        'update' => UpdateEntityRequest::class,
        'delete' => DeleteEntityRequest::class,
        'read' => ReadEntityRequest::class,
        'list' => ListEntityRequest::class
    ];

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        $stub = 'request.stub';
        $name = $this->argument('name');
        foreach (array_keys(static::$requests) as $keyword) {
            if (strpos(strtolower($name), $keyword) !== false) {
                $stub = 'request.api.stub';
                break;
            }
        }

        return $this->resolveStubPath("/stubs/$stub");
    }

    /**
     * Build the class with the given name.
     *
     * Remove the base controller import if we are already in the base namespace.
     *
     * @param string $name
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function buildClass($name)
    {
        $replace = [
            'ParentDummyFullClassName' => EntityRequest::class,
            'ParentDummyModelClass' => 'EntityRequest'
        ];

        foreach (static::$requests as $keyword => $class) {
            if (strpos(strtolower($name), $keyword) !== false) {
                $replace = [
                    'ParentDummyFullClassName' => $class,
                    'ParentDummyModelClass' => substr($class, strrpos($class, '\\') + 1)
                ];
            }
        }

        return str_replace(
            array_keys($replace),
            array_values($replace),
            parent::buildClass($name)
        );
    }

    /**
     * Resolve the fully-qualified path to the stub.
     *
     * @param  string  $stub
     * @return string
     */
    protected function resolveStubPath($stub)
    {
        return file_exists($customPath = $this->laravel->basePath(trim($stub, '/')))
            ? $customPath
            : __DIR__ . $stub;
    }
}
