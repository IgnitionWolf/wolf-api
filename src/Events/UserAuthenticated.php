<?php

namespace IgnitionWolf\API\Events;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserAuthenticated
{
    use Dispatchable, SerializesModels;

    /**
     * @var Model
     */
    public Model $user;

    /**
     * Create a new event instance.
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->user = $model;
    }
}
