<?php

namespace IgnitionWolf\API\Exceptions;

use IgnitionWolf\API\Exceptions\Core\BaseException;

class NotAuthorizedException extends BaseException
{
    public function __construct()
    {
        parent::__construct(
            code: 401,
            message: trans('api::exceptions.not_authorized'),
            prettyCode: 'NOT_AUTHORIZED'
        );
    }
}
