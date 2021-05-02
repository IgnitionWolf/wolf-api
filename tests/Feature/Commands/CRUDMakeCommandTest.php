<?php

namespace IgnitionWolf\API\Tests\Feature\Commands;

use IgnitionWolf\API\Http\Requests\EntityRequest;
use IgnitionWolf\API\Tests\TestCase;
use IgnitionWolf\API\Support\Stub;

class CRUDMakeCommandTest extends TestCase
{
    public function test_it_creates_crud_files()
    {
        $path = $this->app->basePath('app/Http/Requests');
        $requests = $this->toBeTrashed([
            'Create' => "$path/Dummy/CreateRequest",
            'Read' => "$path/Dummy/ReadRequest",
            'Update' => "$path/Dummy/UpdateRequest",
            'Delete' => "$path/Dummy/DeleteRequest",
            'List' => "$path/Dummy/ListRequest"
        ]);

        $this->artisan('make:crud', ['name' => 'Dummy']);

        foreach ($requests as $action => $request) {
            $filename = $request . '.php';
            $name = substr($request, strrpos($request, '/') + 1);
            $this->assertFileExists($filename);

            $content = app(Stub::class)->render('request.api.stub', [
                'ParentDummyFullClassName' => "IgnitionWolf\\API\\Http\\Requests\\${action}EntityRequest",
                'ParentDummyModelClass' => "${action}EntityRequest",
                'DummyNamespace' => 'App\\Http\\Requests\\Dummy',
                'DummyClass' => $name
            ]);

            $this->assertStringEqualsFile($filename, $content);

            unlink($filename);
        }
    }
}
