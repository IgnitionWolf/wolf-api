<?php

namespace IgnitionWolf\API\Strategies\Filter;

use Illuminate\Database\Eloquent\Builder;

interface FilterStrategy
{
    public function filter(\Illuminate\Http\Request $request, Builder $context);
}
