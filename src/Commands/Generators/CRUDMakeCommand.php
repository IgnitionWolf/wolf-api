<?php

namespace IgnitionWolf\API\Commands\Generators;

use Illuminate\Console\Command;

class CRUDMakeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:make-crud {name} {module}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create the CRUD FormRequests and Controller for an Models.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $name = $this->argument('name');
        $module = $this->argument('module');

        foreach (['Create', 'Read', 'Update', 'Delete', 'List'] as $action) {
            $this->call("module:make-request", [
                'name' => "{$name}/{$action}Request",
                'module' => $module
            ]);
        }

        $this->call("module:make-controller", [
            'controller' => "${name}Controller",
            'module' => $module,
            '--api' => true
        ]);
    }
}
