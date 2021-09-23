<?php

namespace IgnitionWolf\API;

use IgnitionWolf\API\Exceptions\Core\ExceptionBridge;
use Illuminate\Support\ServiceProvider;

use IgnitionWolf\API\Exceptions\RouteNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Throwable;

class ExceptionServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->app->singleton(
            'Illuminate\Contracts\Debug\ExceptionHandler',
            'IgnitionWolf\API\Exceptions\Core\Handler'
        );

        if (!app()->environment('production')) {
            if ((int) request()->input('debug', 0) == 1) {
                config(['app.debug' => true]);
            }
        }
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->when(ExceptionBridge::class)
            ->needs('$exceptionBridgeMap')
            ->give($this->getBridgeMap());
    }

    /**
     * Get the equivalent custom exception of a 3rd party provider exception.
     * Mostly used to override Laravel exceptions and format them correctly.
     *
     * @return array<Throwable>
     */
    private function getBridgeMap(): array
    {
        return array_merge(config('api.exceptions_bridge', []), [
            NotFoundHttpException::class => RouteNotFoundException::class
        ]);
    }
}
