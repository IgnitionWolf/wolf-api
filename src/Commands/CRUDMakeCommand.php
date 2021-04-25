<?php

namespace IgnitionWolf\API\Commands;

use Illuminate\Console\Command;

class CRUDMakeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:crud {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create the CRUD FormRequests and Controller for a Model.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $name = $this->argument('name');

        foreach (['Create', 'Read', 'Update', 'Delete', 'List'] as $action) {
            $this->call("make:request", [
                'name' => "{$name}/{$action}Request",
            ]);
        }

        $this->call("make:controller", [
            'controller' => "${name}Controller",
            '--api' => true
        ]);
    }
}
