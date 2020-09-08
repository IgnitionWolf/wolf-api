<?php

namespace IgnitionWolf\API\Entity;

use IgnitionWolf\API\Entity\Automap\Automapable;
use IgnitionWolf\API\Traits\HasRelationships;
use Illuminate\Database\Eloquent\Model as EloquentModel;

class Model extends EloquentModel
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
    
}
