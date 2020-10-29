<?php

namespace IgnitionWolf\API\Requests\Authentication;

use IgnitionWolf\API\Requests\EntityRequest;
use Modules\User\Entities\User;

/**
 * Handles the validation for social authentication.
 */
class SocialRequest extends EntityRequest
{
    protected static $entity = User::class;

    public static $rules = [
        'provider' => 'required|in:google,facebook,instagram',
        'token' => 'required|string'
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

    /**
     * @inheritDoc
     */
    public function authorize()
    {
        return true;
    }
}
