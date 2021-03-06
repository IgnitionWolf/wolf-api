<?php

namespace IgnitionWolf\API\Automap;

/**
 * This allows automatic data assignment.
 */
trait Automapable
{
    /**
     * Automap model attributes that will not be set in requests.
     *
     * @param array $attributes
     * @return self
     */
    public function automap(array $attributes = []): self
    {
        if (!isset($this->map)) {
            return $this;
        }

        foreach ($this->map as $index => $value) {
            if (!empty($attributes)) {
                if (!in_array($index, $attributes)) {
                    continue;
                }
            }

            $transformer = app($value);
            $transformer->map($this, $index);
        }

        return $this;
    }
}
