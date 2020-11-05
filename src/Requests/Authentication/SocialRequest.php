<?php

namespace IgnitionWolf\API\Requests\Authentication;

use IgnitionWolf\API\Requests\EntityRequest;
use Modules\User\Entities\User;

/**
 * Handles the validation for social authentication.
 */
class SocialRequest extends EntityRequest
{
    public static $rules = [
        'provider' => 'required|in:google,facebook,instagram',
        'token' => 'required|string'
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

    /**
     * @inheritDoc
     */
    public function authorize()
    {
        return true;
    }
}
