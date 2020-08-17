<?php

namespace IgnitionWolf\API\Rules;

use Illuminate\Contracts\Foundation\Application;

interface Rule
{
    public function __invoke(Application &$app): void;
}
