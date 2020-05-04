<?php

namespace Spatie\Skeleton;

use Spatie\Skeleton\Exceptions\Core\ExceptionBridge;
use Illuminate\Support\ServiceProvider;

use Spatie\Skeleton\Exceptions\RouteNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Tymon\JWTAuth\Exceptions\JWTException as OriginalJWTException;
use Spatie\Skeleton\Exceptions\JWTException as MyJWTException;

class ExceptionServiceProvider extends ServiceProvider
{
    /**
     * Get the equivalent custom exception of a 3rd party provider exception.
     * Mostly used to override Laravel exceptions and format them correctly.
     *
     * Modify this per your needs.
     *
     * @return array<Throwable>
     */
    private function getBridgeMap(): array
    {
        return  [
            NotFoundHttpException::class => RouteNotFoundException::class,
            OriginalJWTException::class => MyJWTException::class
        ];
    }

    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->app->singleton(
            'Illuminate\Contracts\Debug\ExceptionHandler',
            'Spatie\Skeleton\Exceptions\Core\Handler'
        );
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
}
