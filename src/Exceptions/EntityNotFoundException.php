<?php

namespace IgnitionWolf\API\Exceptions;

use IgnitionWolf\API\Exceptions\Core\BaseException;

class EntityNotFoundException extends BaseException
{
    public function __construct()
    {
        parent::__construct(
            code: 404,
            message: trans('api::exceptions.entity_not_found'),
            prettyCode: 'ENTITY_NOT_FOUND'
        );
    }
}
