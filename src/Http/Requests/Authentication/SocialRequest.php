<?php

namespace IgnitionWolf\API\Http\Requests\Authentication;

use IgnitionWolf\API\Http\Requests\EntityRequest;
use Modules\User\Entities\User;

/**
 * Handles the validation for social authentication.
 */
class SocialRequest extends EntityRequest
{
    public static array $rules = [
        'provider' => 'required|in:google,facebook,instagram',
        'token' => 'required|string'
    ];

    public function __construct()
    {
        parent::__construct();
        static::$model = config('api.user.model');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return static::$rules;
    }

    /**
     * @inheritDoc
     */
    public function authorize(): bool
    {
        return true;
    }
}
