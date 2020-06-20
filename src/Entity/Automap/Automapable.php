<?php

namespace IgnitionWolf\API\Entity\Automap;

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
     */
    public function automap(array $attributes = [])
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

            $transformer = new $value;
            $transformer->map($this, $index);
        }

        return $this;
    }
}
