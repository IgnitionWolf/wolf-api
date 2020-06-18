<?php

namespace IgnitionWolf\API;

use Illuminate\Support\Facades\Facade;

/**
 * @see \IgnitionWolf\API\WolfAPIClass
 */
class WolfAPIFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'wolfapi';
    }
}
