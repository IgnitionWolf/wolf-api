<?php

namespace IgnitionWolf\API\Concerns;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Model;

trait FillsDataFromArray
{
    /**
     * @param array $data
     * @param Model $model
     * @throws BindingResolutionException
     */
    public function fillFromArray(array $data, Model $model)
    {
        $model->fill($data);

        if ($model instanceof \IgnitionWolf\API\Models\Model) {
            $model->fillRelationships(array_intersect($model->getRelationships(), array_keys($data)));
        }

        if (method_exists($this, 'fillTranslatable') && method_exists($model, 'translate')) {
            $this->fillTranslatable($model, $data);
        }
    }
}
