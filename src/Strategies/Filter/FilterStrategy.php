<?php

namespace IgnitionWolf\API\Strategies\Filter;

interface FilterStrategy
{
    public function filter(array $filters, string $context);
}
