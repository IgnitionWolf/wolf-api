<?php

namespace IgnitionWolf\API\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

trait WithHooks
{
    /**
     * This hook is called before persisting a new model entity.
     *
     * @param Request $request
     * @param Model $model
     */
    public function onPreCreate(Request $request, Model $model)
    {
        //
    }

    /**
     * This hook is called after persisting a new model entity.
     *
     * @param Request $request
     * @param Model $model
     */
    public function onPostCreate(Request $request, Model $model)
    {
        // Override this method
    }

    /**
     * This hook is called before updating a model entity.
     *
     * @param Request $request
     * @param Model $model
     */
    public function onPreUpdate(Request $request, Model $model)
    {
        //
    }

    /**
     * This hook is called after updating a model entity.
     *
     * @param Request $request
     * @param Model $model
     */
    public function onPostUpdate(Request $request, Model $model)
    {
        // Override this method
    }
}
