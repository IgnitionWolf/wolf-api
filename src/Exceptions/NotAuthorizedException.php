<?php

namespace IgnitionWolf\API\Exceptions;

use IgnitionWolf\API\Exceptions\Core\BaseException;
use IgnitionWolf\API\Exceptions\Core\ExceptionPayload;

class NotAuthorizedException extends BaseException
{
    /**
     * {@inheritdoc}
     */
    public function getPayload(): ExceptionPayload
    {
        return new ExceptionPayload([
            ExceptionPayload::ARG_MESSAGE => 'You are not authorized to perform that action',
            ExceptionPayload::ARG_IDENTIFIER => 'NOT_AUTHORIZED',
            ExceptionPayload::ARG_STATUS_CODE => 401
        ]);
    }
}
