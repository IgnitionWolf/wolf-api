<?php

namespace IgnitionWolf\API\Requests;

use IgnitionWolf\API\Exceptions\ValidationException;
use IgnitionWolf\API\Traits\Bounces;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use IgnitionWolf\API\Exceptions\NotAuthorizedException;

abstract class EntityRequest extends FormRequest
{
    use Bounces;

    protected static $entity;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new ValidationException($validator);
    }

    /**
     * Handle a failed authorization attempt.
     *
     * @return void
     *
     * @throws NotAuthorizedException
     */
    protected function failedAuthorization()
    {
        throw new NotAuthorizedException;
    }
}
