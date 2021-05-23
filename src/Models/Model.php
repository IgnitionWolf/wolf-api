<?php

namespace IgnitionWolf\API\Models;

use LaravelFillableRelations\Eloquent\Concerns\HasFillableRelations;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Flugg\Responder\Contracts\Transformable;

abstract class Model extends Eloquent implements Transformable
{
    use HasFillableRelations;

    public function transformer()
    {
        return function () {
            return $this->toArray();
        };
    }
}
