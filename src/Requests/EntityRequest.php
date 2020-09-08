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

    /**
     * @psalm-var class-string
     * @var string
     */
    protected static $entity;

    /**
     * @var array
     */
    public static $rules;

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

    public function findEntity(?int $id)
    {
        if (!static::$entity) {
            throw new \Exception('Tried to call findEntity() but the $entity has not been assigned yet.');
        }

        return static::$entity::find($id);
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
