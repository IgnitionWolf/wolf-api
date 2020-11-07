<?php

namespace IgnitionWolf\API\Exceptions;

use IgnitionWolf\API\Exceptions\Core\BaseException;
use IgnitionWolf\API\Exceptions\Core\ExceptionPayload;
use Illuminate\Contracts\Validation\Validator;

class ValidationException extends BaseException
{
    /**
     * {@inheritdoc}
     */
    public function getPayload(): ExceptionPayload
    {
        /**
         * If a Validator instance is passed, we'll do the hard work.
         * If not, an array is assumed.
         */
        if ($this->meta instanceof Validator) {
            $this->meta = $this->meta->errors()->toArray();
        }

        return new ExceptionPayload([
            ExceptionPayload::ARG_MESSAGE => trans('api::exceptions.validation'),
            ExceptionPayload::ARG_IDENTIFIER => 'VALIDATION_ERROR',
            ExceptionPayload::ARG_STATUS_CODE => 400,
            ExceptionPayload::ARG_META => ['errors' => $this->meta]
        ]);
    }
}
