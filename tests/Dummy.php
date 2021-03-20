<?php

namespace IgnitionWolf\API\Tests;

use IgnitionWolf\API\Models\Model;

/**
 * @method static find(int $int)
 */
class Dummy extends Model
{
    protected $fillable = ['name', 'age'];

    public function transformer(): \Closure
    {
        return function ($data) {
            return [
                'id' => $data->id,
                'name' => $data->name
            ];
        };
    }
}
