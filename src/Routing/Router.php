<?php

namespace IgnitionWolf\API\Routing;

use IgnitionWolf\API\Routing\ResourceRegistrar;
use Illuminate\Routing\PendingResourceRegistration;
use Illuminate\Routing\Router as OriginalRouter;

/**
 * Extends the Laravel router to support the correct API resources.
 * @mixin \Illuminate\Routing\RouteRegistrar
 */
class Router extends OriginalRouter
{
    /**
     * Route a resource to a controller.
     *
     * @param  string  $name
     * @param  string  $controller
     * @param  array  $options
     * @return \Illuminate\Routing\PendingResourceRegistration
     */
    public function resource($name, $controller, array $options = [])
    {
        if ($this->container && $this->container->bound(ResourceRegistrar::class)) {
            $registrar = $this->container->make(ResourceRegistrar::class);
        } else {
            $registrar = new ResourceRegistrar($this);
        }

        return new PendingResourceRegistration(
            $registrar,
            $name,
            $controller,
            $options
        );
    }
}
