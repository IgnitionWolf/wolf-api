<?php

namespace IgnitionWolf\API\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

trait WithHooks
{
    /**
     * This hook is called before persisting a new model.
     *
     * @param Request $request
     * @param Model $model
     */
    public function onPreCreate(Request $request, Model $model)
    {
        //
    }

    /**
     * This hook is called after persisting a new model.
     *
     * @param Request $request
     * @param Model $model
     */
    public function onPostCreate(Request $request, Model $model)
    {
        // Override this method
    }
}
