<?php

namespace IgnitionWolf\API\Models;

use LaravelFillableRelations\Eloquent\Concerns\HasFillableRelations;
use Illuminate\Database\Eloquent\Model as BaseModel;
use Flugg\Responder\Transformers\Transformer;
use Flugg\Responder\Contracts\Transformable;

class Model extends BaseModel implements Transformable
{
    use HasFillableRelations;

    /**
     * Get a transformer for the class.
     *
     * @return Transformer|string|callable
     */
    public function transformer()
    {
        return function () {
            return $this->toArray();
        };
    }
}
