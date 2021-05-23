<?php

namespace IgnitionWolf\API\Concerns;

trait HasGarbageCollection
{
    protected array $garbageCollection = [];

    /**
     * Add one or more items to the file trash queue.
     *
     * @param array|string $item
     * @return array|string|null
     */
    public function toBeTrashed($item)
    {
        if (is_string($item)) {
            return $this->garbageCollection[] = $item;
        } elseif (is_array($item)) {
            $this->garbageCollection = array_merge($this->garbageCollection, array_values($item));
            return $item;
        }
        return null;
    }

    /**
     * Delete all the files in the trash queue.
     */
    public function collect(): void
    {
        foreach ($this->garbageCollection as $item) {
            if (file_exists($item)) {
                unlink($item);
            }
        }

        $this->garbageCollection = [];
    }
}
