<?php

namespace IgnitionWolf\API\Requests\Authentication;

use IgnitionWolf\API\Requests\EntityRequest;
use Modules\User\Entities\User;

/**
 * Handles basic validation for registering.
 * This should be extended and used to validate specific rules.
 */
class RegisterRequest extends EntityRequest
{
    protected static $entity = User::class;

    public static $rules = [
        'email' => 'email:filter|required',
        'password' => 'required'
    ];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return self::$rules;
    }
}