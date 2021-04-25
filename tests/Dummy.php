<?php

namespace IgnitionWolf\API\Tests;

use Illuminate\Database\Eloquent\Relations\HasMany;
use IgnitionWolf\API\Models\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @method static find(int $int)
 */
class Dummy extends Model
{
    protected $fillable = ['name', 'age'];

    protected $fillable_relations = ['dummy_children', 'dummy_poly'];

    public function dummyChildren(): HasMany
    {
        return $this->hasMany(DummyChild::class);
    }

    public function dummyPoly(): MorphMany
    {
        return $this->morphMany(DummyPoly::class, 'morphable');
    }

    public function transformer(): \Closure
    {
        return function ($data) {
            return [
                'id' => $data->id,
                'name' => $data->name,
                'dummy_children' => $data->dummyChildren->map(function ($child) {
                    return $child->transformer()($child);
                }),
                'dummy_poly' => $data->dummyPoly->map(function ($child) {
                    return $child->transformer()($child);
                })
            ];
        };
    }
}
