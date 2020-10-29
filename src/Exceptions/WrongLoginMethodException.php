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
            ExceptionPayload::ARG_MESSAGE => 'You registered with a social media, you need to login using with it',
            ExceptionPayload::ARG_IDENTIFIER => 'AUTHENTICATION_METHOD_FAILURE',
            ExceptionPayload::ARG_STATUS_CODE => 401
        ]);
    }
}
