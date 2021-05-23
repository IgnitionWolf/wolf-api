<?php

namespace IgnitionWolf\API\Support;

use Exception;

class Stub
{
    protected string $path = __DIR__ . '/../Commands/stubs';

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
     * Set the path to stubs directory.
     * @param string $path
     * @return Stub
     */
    public function setBasePath(string $path): Stub
    {
        $this->path = $path;
        return $this;
    }

    /**
     * Get the path to stubs directory.
     * @return string
     */
    public function getBasePath(): string
    {
        return $this->path;
    }
}
