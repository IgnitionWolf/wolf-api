<?php

namespace IgnitionWolf\API\Entity;

use IgnitionWolf\API\Entity\Automap\Automapable;
use IgnitionWolf\API\Traits\HasRelationships;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class Model extends EloquentModel
{
    use Automapable;
    use SoftDeletes;
    use HasRelationships;
    
    /**
     * Automapable settings.
     *
     * @var array
     */
    protected $map = [];
}
