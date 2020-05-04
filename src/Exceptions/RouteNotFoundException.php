<?php

namespace Spatie\Skeleton\Exceptions;

use Spatie\Skeleton\Exceptions\Core\BaseException;
use Spatie\Skeleton\Exceptions\Core\ExceptionPayload;

class RouteNotFoundException extends BaseException
{
    /**
     * {@inheritdoc}
     */
    public function getPayload(): ExceptionPayload
    {
        return new ExceptionPayload([
            ExceptionPayload::ARG_MESSAGE => 'We could not find that route',
            ExceptionPayload::ARG_IDENTIFIER => 'NOT_FOUND',
            ExceptionPayload::ARG_STATUS_CODE => 404
        ]);
    }
}
