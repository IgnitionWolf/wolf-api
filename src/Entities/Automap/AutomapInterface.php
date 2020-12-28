<?php

namespace IgnitionWolf\API\Entities\Automap;

use IgnitionWolf\API\Entities\Model;

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
