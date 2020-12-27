<?php

namespace IgnitionWolf\API\Traits;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

trait FillsDataFromRequest
{
    /**
     * @param FormRequest|Request $request
     * @param Model $model
     * @throws BindingResolutionException
     */
    public function fillFromRequest($request, Model $model)
    {
        $validAttributes = $request->validated();

        $model->fill($validAttributes);

        if ($model instanceof \IgnitionWolf\API\Entity\Model) {
            $model->automap();

            $model->fillRelationships($request->only(
                array_intersect($model->getRelationships(), array_keys($validAttributes))
            ));
        }

        if (method_exists($this, 'fillTranslatable') && method_exists($model, 'translate')) {
            $this->fillTranslatable($model, $validAttributes);
        }
    }
}
