<?php

namespace IgnitionWolf\API\Entity;

use Flugg\Responder\Contracts\Transformable;
use Flugg\Responder\Transformers\Transformer;
use IgnitionWolf\API\Entity\Automap\Automapable;
use IgnitionWolf\API\Traits\HasRelationships;
use Illuminate\Database\Eloquent\Model as EloquentModel;

class Model extends EloquentModel implements Transformable
{
    use Automapable;
    use HasRelationships;

    /**
     * Automapable settings.
     *
     * @var array
     */
    protected $map = [];

    /**
     * Translations (support for spatie/translatable)
     *
     * @var string[]
     */
    protected $translatable = [];

    /**
     * Get a transformer for the class.
     *
     * @return Transformer|string|callable
     */
    public function transformer()
    {
        return function() {
            return $this->toArray();
        };
    }
}
