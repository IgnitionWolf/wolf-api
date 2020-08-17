<?php

namespace IgnitionWolf\API\Exceptions;

use IgnitionWolf\API\Exceptions\Core\BaseException;
use IgnitionWolf\API\Exceptions\Core\ExceptionPayload;

class VerificationCodeException extends BaseException
{
    /**
     * {@inheritdoc}
     */
    public function getPayload(): ExceptionPayload
    {
        return new ExceptionPayload([
            ExceptionPayload::ARG_MESSAGE => $this->meta[0],
            ExceptionPayload::ARG_IDENTIFIER => $this->meta[1],
            ExceptionPayload::ARG_STATUS_CODE => 400
        ]);
    }
}
