<?php

namespace IgnitionWolf\API\Traits;

use Exception;
use IgnitionWolf\API\Entity\Authenticatable;
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
     * @param string|object $entity
     * @return bool
     * @throws Exception
     */
    public function can(string $action, $entity): bool
    {
        $user = $this->getCurrentUser();

        if (!$user) {
            return false;
        }

        if (!method_exists($user, 'can')) {
            throw new Exception('Trying to use can() in a FormRequest and User is not using Bouncer traits.');
        }

        return $user->can($action, $entity);
    }

    /**
     * Get the current user by parsing the request Authentication header.
     *
     * @param bool $cached
     * @return Authenticatable
     */
    public function getCurrentUser($cached = true)
    {
        if (!$this->currentUser || !$cached) {
            $this->currentUser = JWTAuth::parseToken()->toUser();
        }

        return $this->currentUser;
    }
}
