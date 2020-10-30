<?php

namespace IgnitionWolf\API\Entity;

use Flugg\Responder\Contracts\Transformable;
use Flugg\Responder\Transformers\Transformer;
use IgnitionWolf\API\Entity\Automap\Automapable;
use IgnitionWolf\API\Traits\HasRelationships;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User;
use Silber\Bouncer\Database\HasRolesAndAbilities;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Authenticatable extends User implements JWTSubject, Transformable
{
    use Automapable;
    use SoftDeletes;
    use HasRelationships;
    use HasRolesAndAbilities;

    /**
     * Automapable settings.
     *
     * @var array
     */
    protected $map = [];

    /**
     * Get a transformer for the class.
     *
     * @return Transformer|string|callable
     */
    public function transformer()
    {
        return null;
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
