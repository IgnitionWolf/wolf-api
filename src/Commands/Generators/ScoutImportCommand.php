<?php

namespace IgnitionWolf\API\Commands\Generators;

use Illuminate\Console\Command;
use Laravel\Scout\Searchable;

class ScoutImportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:scout:import {name} {module} {--force}';

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

        $lookup = base_path("Modules/$module/Entities/$name.php");
        foreach (glob($lookup) as $path) {
            $model = substr($path, strpos($path, 'Modules'));
            $model = str_replace('.php', '', $model);
            $model = str_replace('/', '\\', $model);

            try {
                /**
                 * Check if the model is searchable, because we don't want to index unnecessary data.
                 */
                $modelReflection = new \ReflectionClass($model);
                if (!in_array(
                    Searchable::class,
                    array_keys($modelReflection->getTraits())
                )) {
                    continue;
                }

                $this->call("scout:import", [
                    'model' => $model,
                ]);
            } catch (\Exception $e) {}
        }
    }
}
