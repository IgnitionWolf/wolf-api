<?php

namespace IgnitionWolf\API\Models\Automap;

use IgnitionWolf\API\Models\Model;

interface AutomapInterface
{
    /**
     * Automatically map an entity attribute.
     *
     * @param Model $entity
     * @param string $attribute
     * @return mixed
     */
    public function map(Model $entity, string $attribute);
}
