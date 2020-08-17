<?php

return [

    /*
    |--------------------------------------------------------------------------
    | API Version
    |--------------------------------------------------------------------------
    | The API version that will be used in route formatting.
    |
    */
    'version' => '1',

    /*
    |--------------------------------------------------------------------------
    | Intercept Exceptions
    |--------------------------------------------------------------------------
    |
    | The exceptions that need to be bridged. This will transform a exception into other.
    | Mostly useful to intercept Laravel default exceptions with API exceptions.
    |
    */
    'exceptions_bridge' => [],

    /*
    |--------------------------------------------------------------------------
    | User
    |--------------------------------------------------------------------------
    |
    | You can configure user-related variables here.
    |
    */
    'user' => [
        'model' => \Modules\User\Entities\User::class,
        'verifications' => true
    ]
];
