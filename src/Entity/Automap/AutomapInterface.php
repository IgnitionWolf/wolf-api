<?php

namespace IgnitionWolf\API\Entity\Automap;

use IgnitionWolf\API\Entity\Model;

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
