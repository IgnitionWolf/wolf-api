<?php

namespace IgnitionWolf\API\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

trait FillsDataFromRequest
{
    /**
     * @param FormRequest|Request|array $request
     * @param Model $model
     */
    public function fillFromRequest($request, Model $model)
    {
        $validAttributes = $request->validated();

        $model->fill($validAttributes);

        if ($model instanceof \IgnitionWolf\API\Models\Model) {
            $model->automap();
        }

//        if (method_exists($this, 'fillTranslatable') && method_exists($model, 'translate')) {
//            $this->fillTranslatable($model, $validAttributes);
//        }
    }
}
