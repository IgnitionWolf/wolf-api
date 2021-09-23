<?php

namespace IgnitionWolf\API\Concerns;

use Illuminate\Http\Request;

trait WithHooks
{
    /**
     * This hook is called before persisting a new model entity.
     *
     * @param Request $request
     * @param mixed $model
     */
    public function onPreCreate(Request $request, mixed $model)
    {
        //
    }

    /**
     * This hook is called after persisting a new model entity.
     *
     * @param Request $request
     * @param mixed $model
     */
    public function onPostCreate(Request $request, mixed $model)
    {
        //
    }

    /**
     * This hook is called before updating a model entity.
     *
     * @param Request $request
     * @param mixed $model
     */
    public function onPreUpdate(Request $request, mixed $model)
    {
        //
    }

    /**
     * This hook is called after updating a model entity.
     *
     * @param Request $request
     * @param mixed $model
     */
    public function onPostUpdate(Request $request, mixed $model)
    {
        //
    }
}
