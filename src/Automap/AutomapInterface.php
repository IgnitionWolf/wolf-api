<?php

namespace IgnitionWolf\API\Automap;

use Illuminate\Database\Eloquent\Model;

interface AutomapInterface
{
    /**
     * Automatically map an entity attribute.
     *
     * @param \Illuminate\Database\Eloquent\Model $entity
     * @param string $attribute
     * @return mixed
     */
    public function map(Model $entity, string $attribute);
}
