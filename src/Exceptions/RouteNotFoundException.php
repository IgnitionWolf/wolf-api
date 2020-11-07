<?php

namespace IgnitionWolf\API\Exceptions;

use IgnitionWolf\API\Exceptions\Core\BaseException;
use IgnitionWolf\API\Exceptions\Core\ExceptionPayload;

class RouteNotFoundException extends BaseException
{
    /**
     * {@inheritdoc}
     */
    public function getPayload(): ExceptionPayload
    {
        return new ExceptionPayload([
            ExceptionPayload::ARG_MESSAGE => trans('api::exceptions.not_found'),
            ExceptionPayload::ARG_IDENTIFIER => 'NOT_FOUND',
            ExceptionPayload::ARG_STATUS_CODE => 404
        ]);
    }
}
