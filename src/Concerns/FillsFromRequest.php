<?php

namespace IgnitionWolf\API\Concerns;

use Illuminate\Database\Eloquent\Model;

trait FillsFromRequest
{
    public function fillFromRequest($request, Model $model)
    {
        $model->fill($request->validated());

        if (method_exists($model, 'automap')) {
            $model->automap();
        }
    }
}
