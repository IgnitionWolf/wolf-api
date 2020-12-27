<?php

namespace IgnitionWolf\API\Events;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserRegistered
{
    use Dispatchable, SerializesModels;

    /**
     * @var Model
     */
    public Model $user;

    /**
     * Data provided by the 3rd party registration provider.
     * @var object
     */
    public $providerData;

    /**
     * Create a new event instance.
     * @param Model $model
     * @param $providerData
     */
    public function __construct(Model $model, $providerData = null)
    {
        $this->user = $model;
        $this->providerData = $providerData;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
