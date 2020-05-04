<?php

namespace Spatie\Skeleton\Exceptions\Core;

use Exception;
use Flugg\Responder\Facades\Responder;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Spatie\Skeleton\Exceptions\Core\ExceptionPayload as Payload;
use Throwable;

/**
 * This exception handler overrides the Laravel's default.
 * You can look for a deeper explanation below.
 */
class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function render($request, Throwable $exception)
    {
        self::intercept($exception);

        $data = [
            'statusCode' => null,
            'message' => null,
            'code' => null,
            'meta' => null
        ];

        /**
         * Here we are looking at three different types of exceptions:
         * BaseException (this package's exception)
         * HttpException (laravel's exception)
         * Exception (PHP default exception)
         *
         * We'll preparate the data accordingly.
         */
        if ($exception instanceof BaseException) {
            $data['message'] = $exception->get(Payload::ARG_MESSAGE);
            $data['statusCode'] = $exception->get(Payload::ARG_STATUS_CODE);
            $data['code'] = $exception->get(Payload::ARG_IDENTIFIER);
            $data['meta'] = $exception->get(Payload::ARG_META);
        } elseif ($exception instanceof HttpException) {
            $data['message'] = $exception->getMessage();
            $data['statusCode'] = $exception->getStatusCode();
        } else {
            $data['message'] = $exception->getMessage();
            $data['statusCode'] = Payload::$defaults[Payload::ARG_STATUS_CODE];
            $data['code'] = Payload::$defaults[Payload::ARG_IDENTIFIER];
        }

        /**
         * If we're in production or non-debug mode we shouldn't disclose any technical error data.
         */
        if (!$exception instanceof BaseException) {
            if (App::environment('production')) {
                $data['message'] = Payload::$defaults[Payload::ARG_MESSAGE];
            }

            if (Config::get('app.debug')) {
                return parent::render($request, $exception);
            }
        }

        $response = Responder::error($data['code'] ?? Payload::$defaults[Payload::ARG_IDENTIFIER], $data['message']);

        /**
         * "meta" is the extra errors (such as validation errors)
         * This is for a more detailed description of the occured errors.
         */
        if ($data['meta'] && is_array($data['meta'])) {
            $response->data($data['meta']);
        }

        return $response->respond($data['statusCode'] ?? Payload::$defaults[Payload::ARG_STATUS_CODE]);
    }

    /**
     * Wrapper function to ExceptionBridge.
     * This checks if the given exception is overriden by another.
     *
     * @throws Throwable
     * @return void
     */
    private static function intercept(\Throwable $exception)
    {
        $bridge = App::make(ExceptionBridge::class);
        $bridge->intercept($exception);
    }
}
