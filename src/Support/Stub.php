<?php

namespace IgnitionWolf\API\Support;

use Exception;

class Stub
{
    /**
     * Generate a stub file and store it in a specific path, you can pass a set of parameters
     * that will be injected in the stub if necessary.
     *
     * @param string $filename
     * @param array $parameters
     * @throws Exception
     * @return string
     */
    public function render(string $filename, array $parameters = []): string
    {
        $path = ($filename[0] === '/') ? $filename : $this->getBasePath() . '/' . $filename;
        if (!$content = file_get_contents($path)) {
            throw new Exception("Stub '$path' is empty.");
        }

        $this->replaceStubParameters($content, $parameters);
        return $content;
    }

    /**
     * Replace parameter placeholders like {{parameter}} by provided values.
     * @param string $content
     * @param array $parameters
     */
    private function replaceStubParameters(string &$content, array $parameters): void
    {
        foreach ($parameters as $key => $value) {
            $content = str_replace($key, $value, $content);
        }
    }

    /**
     * Get the path to stubs directory.
     * @return string
     */
    private function getBasePath(): string
    {
        return __DIR__ . '/../Commands/stubs';
    }
}
