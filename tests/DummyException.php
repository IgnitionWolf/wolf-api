<?php

namespace IgnitionWolf\API\Tests;

use IgnitionWolf\API\Exceptions\Core\BaseException;

class DummyException extends BaseException
{
    public function __construct()
    {
        parent::__construct(
            code: 400,
            message: 'DummyException',
            prettyCode: 'DUMMY_EXCEPTION',
            meta: ['errors' => ['key' => 'value']]
        );
    }
}
