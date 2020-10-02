<?php

namespace IgnitionWolf\API\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;

class DebugParameter
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /**
         * Check if debug parameter is being set on request level
         * and configure the application accordingly
         *
         * This is used for testing and troubleshooting purposes.
         */
        if (!App::environment('production')) {
            if ((int) $request->get('debug', 0) == 1) {
                Config::set('app.debug', true);
            }
        }
        return $next($request);
    }
}
