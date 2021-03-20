<?php

namespace IgnitionWolf\API\Models;

use Flugg\Responder\Contracts\Transformable;
use Flugg\Responder\Transformers\Transformer;
use IgnitionWolf\API\Models\Automap\Automapable;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use LaravelFillableRelations\Eloquent\Concerns\HasFillableRelations;

class Model extends EloquentModel implements Transformable
{
    use Automapable, HasFillableRelations;

    /**
     * Automapable settings.
     *
     * @var array
     */
    protected array $map = [];

    /**
     * Translations (support for spatie/translatable).
     *
     * @var string[]
     */
    protected array $translatable = [];

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
