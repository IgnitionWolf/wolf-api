<?php

namespace IgnitionWolf\API\Services;

use Exception;

/**
 * This service is used to automatically generate files from stubs.
 */
class GeneratorService
{
    /**
     * Generate a stub file and store it in a specific path, you can pass a set of parameters
     * that will be injected in the stub if necessary.
     *
     * @param string $stub The stub file name.
     * @param string $path The target path.
     * @param array $parameters The parameters in a param => value format.
     *
     * @throws Exception
     *
     * @return bool
     */
    public function generate(string $stub, string $path, array $parameters = []): ?bool
    {
        if (!directoryExists(basename($path))) {
            throw new Exception(sprintf("Directory '%s' does not exist.", basename($path)));
        }

        if (!$content = $this->getStubContent($stub)) {
            throw new Exception(sprintf("Stub '%s' is empty.", $stub));
        }

        $this->replaceStubParameters($content, $parameters);

        file_put_contents($path, $content);

        return true;
    }

    /**
     * Replace parameter placeholders like {{parameter}} by provided values.
     */
    private function replaceStubParameters(string &$content, array $parameters): void
    {
        foreach ($parameters as $parameter => $value) {
            $content = str_replace("{{$parameter}}", $value, $content);
        }
    }

    /**
     * Get the content of a stub file.
     *
     * @return null|string
     */
    private function getStubContent(string $stub): ?string
    {
        $path = sprintf("%s../Stubs/%s.stub", dirname(__FILE__), str_replace('.stub', '', $stub));

        if (!file_exists($path)) {
            return null;
        }

        return file_get_contents($path);
    }
}
