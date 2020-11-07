<?php

namespace IgnitionWolf\API\Exceptions;

use IgnitionWolf\API\Exceptions\Core\BaseException;
use IgnitionWolf\API\Exceptions\Core\ExceptionPayload;

class EntityNotFoundException extends BaseException
{
    /**
     * {@inheritdoc}
     */
    public function getPayload(): ExceptionPayload
    {
        return new ExceptionPayload([
            ExceptionPayload::ARG_MESSAGE => trans('api::exceptions.entity_not_found'),
            ExceptionPayload::ARG_IDENTIFIER => 'ENTITY_NOT_FOUND',
            ExceptionPayload::ARG_STATUS_CODE => 404
        ]);
    }
}
