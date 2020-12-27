<?php

namespace IgnitionWolf\API\Commands\Generators;

use IgnitionWolf\API\Http\Requests\CreateEntityRequest;
use IgnitionWolf\API\Http\Requests\DeleteEntityRequest;
use IgnitionWolf\API\Http\Requests\EntityRequest;
use IgnitionWolf\API\Http\Requests\ListEntityRequest;
use IgnitionWolf\API\Http\Requests\ReadEntityRequest;
use IgnitionWolf\API\Http\Requests\UpdateEntityRequest;
use Nwidart\Modules\Commands\RequestMakeCommand as OriginalRequestMakeCommand;
use Nwidart\Modules\Support\Stub;

class RequestMakeCommand extends OriginalRequestMakeCommand
{
    /**
     * @return mixed
     */
    protected function getTemplateContents()
    {
        $module = $this->laravel['modules']->findOrFail($this->getModuleName());

        $types = [
            'create' => CreateEntityRequest::class,
            'read' => ReadEntityRequest::class,
            'update' => UpdateEntityRequest::class,
            'delete' => DeleteEntityRequest::class,
            'list' => ListEntityRequest::class
        ];

        $type = EntityRequest::class;

        foreach ($types as $action => $class) {
            if (strpos($this->argument('name'), ucfirst($action)) !== false) {
                $type = $class;
            }
        }

        $stub = '/request.stub';
        if ($type === UpdateEntityRequest::class) {
            $stub = '/request-update.stub';
            $parent = str_replace('Update', 'Create', $this->argument('name'));

            // In case of {entity}/{action}Request format
            if ($idx = strpos($parent, '/')) {
                $parent = substr($parent, $idx+1);
            }

            return (new Stub($stub, [
                'NAMESPACE' => $this->getClassNamespace($module),
                'CLASS'     => $this->getClass(),
                'PARENT_CLASS' => $parent
            ]))->render();
        } elseif ($type === CreateEntityRequest::class) {
            $stub = '/request-create.stub';
        } elseif ($type === ListEntityRequest::class) {
            $stub = '/request-list.stub';
        } elseif ($type === EntityRequest::class) {
            $stub = '/request-plain.stub';
        }

        return (new Stub($stub, [
            'NAMESPACE' => $this->getClassNamespace($module),
            'CLASS'     => $this->getClass(),
            'PARENT_CLASS_NAMESPACE' => $type,
            'PARENT_CLASS' => substr($type, strrpos($type, '\\')+1)
        ]))->render();
    }
}
