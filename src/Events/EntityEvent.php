<?php

namespace IgnitionWolf\API\Events;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;

class EntityEvent
{
    use SerializesModels;

    /**
     * @var Model
     */
    public $entity;

    /**
     * @var Request
     */
    public $request;

    /**
     * Create a new event instance.
     * @param Model $model
     * @param Request|null $request
     * @return void
     */
    public function __construct(Model $model, Request $request = null)
    {
        $this->entity = $model;
        if ($request == null) {
            $this->request = request();
        }
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
