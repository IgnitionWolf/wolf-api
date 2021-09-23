<?php

namespace IgnitionWolf\API\Exceptions;

use IgnitionWolf\API\Exceptions\Core\BaseException;

class RouteNotFoundException extends BaseException
{
    public function __construct()
    {
        parent::__construct(
            code: 404,
            message: trans('api::exceptions.not_found'),
            prettyCode: 'NOT_FOUND'
        );
    }
}
