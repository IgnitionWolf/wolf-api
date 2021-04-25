<?php

namespace IgnitionWolf\API\Commands;

use Illuminate\Console\GeneratorCommand;

class AutomapMakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:automap';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new automap class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Automap';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub(): string
    {
        return __DIR__ . '/stubs/automap.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace . '\Automap';
    }
}
