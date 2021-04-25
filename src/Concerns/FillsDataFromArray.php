<?php

namespace IgnitionWolf\API\Concerns;

use Illuminate\Database\Eloquent\Model;

trait FillsDataFromArray
{
    /**
     * @param array $data
     * @param Model $model
     */
    public function fillFromArray(array $data, Model $model)
    {
        $model->fill($data);
    }
}
