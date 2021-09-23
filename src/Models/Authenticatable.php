<?php

namespace IgnitionWolf\API\Models;

use Illuminate\Foundation\Auth\User;
use IgnitionWolf\FillableRelations\HasFillableRelations;
use Flugg\Responder\Contracts\Transformable;

abstract class Authenticatable extends User implements Transformable
{
    use HasFillableRelations;
}
