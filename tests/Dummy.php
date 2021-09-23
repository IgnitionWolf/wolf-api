<?php

namespace IgnitionWolf\API\Tests;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use IgnitionWolf\API\Models\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @method static find(int $int)
 */
class Dummy extends Model
{
    protected $fillable = ['name', 'age'];

    protected $fillable_relations = ['dummy_child', 'dummy_children', 'dummy_poly'];

    public function dummyChild(): BelongsTo
    {
        return $this->belongsTo(DummyChild::class);
    }

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
            $child = $data->dummyChild;
            return [
                'id' => $data->id,
                'name' => $data->name,
                'dummy_children' => $data->dummyChildren->map(function ($child) {
                    return $child->transformer()($child);
                }),
                'dummy_poly' => $data->dummyPoly->map(function ($child) {
                    return $child->transformer()($child);
                }),
                'dummy_child' => $child ? $child->transformer()($child) : null
            ];
        };
    }
}
