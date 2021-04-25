<?php

namespace IgnitionWolf\API\Tests;

use IgnitionWolf\API\Models\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class DummyPoly extends Model
{
    protected $table = 'dummy_poly';

    protected $fillable = ['name', 'age'];

    protected $fillable_relations = ['dummy'];

    public function dummy(): MorphTo
    {
        //return $this->morphTo(Dummy::class, 'morphable');
    }

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
