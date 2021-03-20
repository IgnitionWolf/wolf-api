<?php

namespace IgnitionWolf\API\Commands\Generators;

use Illuminate\Console\Command;

class ScoutFlushCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:scout:flush {name} {module} {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Flush all of the model's records from the index in a given module";

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $name = $this->argument('name');
        $module = $this->argument('module');
        $force = $this->option('force');

        if ($name === '*' && !$force) {
            if (!$this->confirm('Do you wish to continue? You are using a wildcard and everything will be erased')) {
                return 0;
            }
        }

        $lookup = base_path("Modules/$module/Models/$name.php");
        foreach (glob($lookup) as $path) {
            $model = substr($path, strpos($path, 'Modules'));
            $model = str_replace('.php', '', $model);
            $model = str_replace('/', '\\', $model);

            try {
                $this->call("scout:flush", [
                    'model' => $model,
                ]);
            }
            catch(\Exception $e) {}
        }
    }
}
