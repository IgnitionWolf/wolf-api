<?php

namespace IgnitionWolf\API\Entity\Automap;

use Illuminate\Contracts\Container\BindingResolutionException;

/**
 * Make an entity auto-mapable, this allows automatic data assignment upon creation.
 */
trait Automapable
{
    /**
     * Automap model attributes that will not be set in requests.
     *
     * @param array $attributes
     * @return self
     * @throws BindingResolutionException
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

            $transformer = app()->make($value);
            $transformer->map($this, $index);
        }

        return $this;
    }
}
