<?php

namespace IgnitionWolf\API\Http\Requests\Authentication;

use IgnitionWolf\API\Http\Requests\EntityRequest;
use Modules\User\Entities\User;

/**
 * Handles basic validation for registering.
 * This should be extended and used to validate specific rules.
 */
class LoginRequest extends EntityRequest
{
    public static array $rules = [
        'email' => 'email:filter|required',
        'password' => 'required'
    ];

    public function __construct()
    {
        parent::__construct();
        static::$model = config('api.user.model');
    }

    /**
     * @inheritDoc
     */
    public function authorize(): boolean
    {
        return true;
    }
}
