<?php

namespace IgnitionWolf\API\Tests;

use IgnitionWolf\API\Models\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DummyChild extends Model
{
    protected $fillable = ['name', 'age'];

    public function dummy(): BelongsTo
    {
        return $this->belongsTo(Dummy::class);
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
