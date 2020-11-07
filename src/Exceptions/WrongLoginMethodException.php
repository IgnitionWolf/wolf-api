<?php

namespace IgnitionWolf\API\Exceptions;

use IgnitionWolf\API\Exceptions\Core\BaseException;
use IgnitionWolf\API\Exceptions\Core\ExceptionPayload;

class WrongLoginMethodException extends BaseException
{
    /**
     * {@inheritdoc}
     */
    public function getPayload(): ExceptionPayload
    {
        return new ExceptionPayload([
            ExceptionPayload::ARG_MESSAGE => trans('api::exceptions.wrong_login'),
            ExceptionPayload::ARG_IDENTIFIER => 'AUTHENTICATION_METHOD_FAILURE',
            ExceptionPayload::ARG_STATUS_CODE => 401
        ]);
    }
}
