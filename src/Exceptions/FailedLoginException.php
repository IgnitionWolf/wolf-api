<?php

namespace IgnitionWolf\API\Exceptions;

use IgnitionWolf\API\Exceptions\Core\BaseException;
use IgnitionWolf\API\Exceptions\Core\ExceptionPayload;

class FailedLoginException extends BaseException
{
    /**
     * {@inheritdoc}
     */
    public function getPayload(): ExceptionPayload
    {
        return new ExceptionPayload([
            ExceptionPayload::ARG_MESSAGE => trans('api::exceptions.failed_login'),
            ExceptionPayload::ARG_IDENTIFIER => 'AUTHENTICATION_FAILURE',
            ExceptionPayload::ARG_STATUS_CODE => 401
        ]);
    }
}
