<?php

namespace IgnitionWolf\API\Traits;

use Exception;
use Illuminate\Contracts\Auth\Authenticatable;

trait Bounces
{
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
     * @return Authenticatable|null
     */
    public function getCurrentUser(): ?Authenticatable
    {
        return auth()->user();
    }
}
