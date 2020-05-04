<?php

namespace Spatie\Skeleton\Exceptions;

use Spatie\Skeleton\Exceptions\Core\BaseException;
use Spatie\Skeleton\Exceptions\Core\ExceptionPayload;
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
            ExceptionPayload::ARG_MESSAGE => 'We found one or more errors after validating your request.',
            ExceptionPayload::ARG_IDENTIFIER => 'VALIDATION_ERROR',
            ExceptionPayload::ARG_STATUS_CODE => 400,
            ExceptionPayload::ARG_META => ['errors' => $this->meta]
        ]);
    }
}
