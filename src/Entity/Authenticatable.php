<?php

namespace IgnitionWolf\API\Entity;

use IgnitionWolf\API\Entity\Automap\Automapable;
use IgnitionWolf\API\Traits\HasRelationships;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User;

class Authenticatable extends User
{
    use Automapable;
    use SoftDeletes;
    use HasRelationships;
}
