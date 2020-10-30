<?php

namespace IgnitionWolf\API\Events;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Queue\SerializesModels;

class UserSocialRegistered
{
    use SerializesModels;

    /**
     * @var Model
     */
    public $user;

    /**
     * Data provided by the 3rd party registration provider.
     * @var array
     */
    public $providerData;

    /**
     * Create a new event instance.
     * @param Model $model
     * @param $providerData
     */
    public function __construct(Model $model, $providerData)
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
