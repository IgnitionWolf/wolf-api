<?php

namespace IgnitionWolf\API\Traits;

use Tymon\JWTAuth\Facades\JWTAuth;

trait Bounces
{
    /**
     * Cache current user in memory.
     */
    protected $currentUser;

    /**
     * Wrapper function to determine if an user can do a specific action.
     *
     * @param string $action
     * @param Model $entity
     * @return bool
     */
    public function can(string $action, $entity): bool
    {
        $user = $this->getCurrentUser();

        if (!$user) {
            return false;
        }

        return $user->can($action, $entity);
    }

    /**
     * Get the current user by parsing the request Authentication header.
     *
     * @return User
     */
    public function getCurrentUser($cached = true)
    {
        if (!$this->currentUser || !$cached) {
            $this->currentUser = JWTAuth::parseToken()->toUser();
        }

        return $this->currentUser;
    }
}
