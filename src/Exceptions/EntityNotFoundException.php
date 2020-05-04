<?php

namespace Spatie\Skeleton\Exceptions;

use Spatie\Skeleton\Exceptions\Core\BaseException;
use Spatie\Skeleton\Exceptions\Core\ExceptionPayload;

class EntityNotFoundException extends BaseException
{
    /**
     * {@inheritdoc}
     */
    public function getPayload(): ExceptionPayload
    {
        return new ExceptionPayload([
            ExceptionPayload::ARG_MESSAGE => 'We could not find that entity',
            ExceptionPayload::ARG_IDENTIFIER => 'ENTITY_NOT_FOUND',
            ExceptionPayload::ARG_STATUS_CODE => 404
        ]);
    }
}
