<?php

namespace IgnitionWolf\API\Exceptions;

use IgnitionWolf\API\Exceptions\Core\BaseException;

class NotAuthenticatedException extends BaseException
{
    public function __construct()
    {
        parent::__construct(
            code: 401,
            message: trans('api::exceptions.not_authenticated'),
            prettyCode: 'NOT_AUTHENTICATED'
        );
    }
}
