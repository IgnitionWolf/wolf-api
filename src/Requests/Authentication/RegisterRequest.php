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
    public static $rules = [
        'email' => 'email:filter|unique:users,email|required',
        'password' => 'required'
    ];

    public function __construct()
    {
        parent::__construct();
        static::$entity = config('api.user.model');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return static::$rules;
    }
}
