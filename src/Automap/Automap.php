<?php

namespace IgnitionWolf\API\Automap;

use IgnitionWolf\API\Models\Model;

interface Automap
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
