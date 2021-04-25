<?php

namespace IgnitionWolf\API\Tests\Unit;

use IgnitionWolf\API\Exceptions\NotAuthorizedException;
use IgnitionWolf\API\Http\Requests\EntityRequest;
use IgnitionWolf\API\Tests\DummyPoly;
use IgnitionWolf\API\Tests\TestCase;
use IgnitionWolf\API\Validator\RequestValidator;

class RequestValidatorTest extends TestCase
{
    public function test_it_provides_request()
    {
        $this->expectException(NotAuthorizedException::class);
        $request = app(RequestValidator::class)->validate(DummyPoly::class, 'create');
        $this->assertInstanceOf(EntityRequest::class, $request);
    }

    public function test_it_provides_request_options()
    {
        $options = app(RequestValidator::class)->getOptions('App', 'User', 'Create');

        $this->assertContains('App\\Http\\Requests\\CreateUserRequest', $options);
        $this->assertContains('App\\Http\\Requests\\User\\CreateRequest', $options);
        $this->assertContains('IgnitionWolf\\API\\Http\\Requests\\EntityRequest', $options);
    }
}
