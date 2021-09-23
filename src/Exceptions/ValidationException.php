<?php

namespace IgnitionWolf\API\Exceptions;

use IgnitionWolf\API\Exceptions\Core\BaseException;
use Illuminate\Contracts\Validation\Validator;

class ValidationException extends BaseException
{
    public function __construct(mixed $meta)
    {
        if ($this->meta instanceof Validator) {
            $this->meta = $this->meta->errors()->toArray();
        }

        parent::__construct(
            code: 400,
            message: trans('api::exceptions.validation'),
            prettyCode: 'VALIDATION_ERROR',
            meta: ['errors' => $meta]
        );
    }
}
