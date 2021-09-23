<?php

namespace IgnitionWolf\API\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;

trait FillsFromRequest
{
    public function fillFromRequest(FormRequest $request, Model $model)
    {
        $valid = array_merge($model->getFillable(), $model?->fillableRelations());

        $data = array_filter($request->validated(), function ($value, $key) use ($valid) {
            return in_array($key, $valid);
        }, ARRAY_FILTER_USE_BOTH);

        $model->fill($data);

        if (method_exists($model, 'automap')) {
            $model->automap();
        }
    }
}
