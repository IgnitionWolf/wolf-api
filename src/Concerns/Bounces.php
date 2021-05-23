<?php

namespace IgnitionWolf\API\Concerns;

use Exception;
use Illuminate\Support\Facades\Gate;

trait Bounces
{
    protected Gate $gate;

    /**
     * Wrapper function to determine if an user can do a specific action.
     *
     * @param string $action
     * @param string|object $entity
     * @return bool
     * @throws Exception
     */
    public function can(string $action, $entity): bool
    {
        if (!auth()->check()) {
            return false;
        }

        return Gate::allows($action, $entity);
    }
}
