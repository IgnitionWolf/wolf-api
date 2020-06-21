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
            if ($request->has('_debug') || $request->has('debug') || $request->has('dd')) {
                Config::set('app.debug', $request->get('_debug') ?? $request->get('debug') ?? $request->get('dd') == 1);
            }
        }
        return $next($request);
    }
}