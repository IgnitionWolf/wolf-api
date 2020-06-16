<?php

namespace IgnitionWolf\API\Exceptions;

use IgnitionWolf\API\Exceptions\Core\BaseException;
use IgnitionWolf\API\Exceptions\Core\ExceptionPayload;

class JWTException extends BaseException
{
    /**
     * {@inheritdoc}
     */
    public function getPayload(): ExceptionPayload
    {
        return new ExceptionPayload([
            ExceptionPayload::ARG_MESSAGE => $this->message,
            ExceptionPayload::ARG_IDENTIFIER => 'AUTHENTICATION_ERROR',
            ExceptionPayload::ARG_STATUS_CODE => 400
        ]);
    }
}
