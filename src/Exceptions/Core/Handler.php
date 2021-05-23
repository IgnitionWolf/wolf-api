<?php

namespace IgnitionWolf\API\Exceptions\Core;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use IgnitionWolf\API\Exceptions\Core\ExceptionPayload as Payload;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use Throwable;

/**
 * This exception handler overrides the Laravel's default.
 * You can look for a deeper explanation below.
 */
class Handler extends ExceptionHandler
{
    /**
     * Report or log an exception.
     *
     * @param Throwable $e
     * @return void
     * @throws Throwable
     */
    public function report(Throwable $e)
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param Request $request
     * @param Throwable $exception
     * @return Response
     * @throws Throwable
     */
    public function render($request, Throwable $exception)
    {
        app(ExceptionBridge::class)->intercept($exception);

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
         * We'll prepare the data accordingly.
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
            $data['statusCode'] = (!empty($code = $exception->getCode()) && ($code >= 400 && $code <= 600))
                                    ? $code
                                    : Payload::$defaults[Payload::ARG_STATUS_CODE];
            $data['code'] = Payload::$defaults[Payload::ARG_IDENTIFIER];
        }

        /**
         * If we're in production or non-debug mode we shouldn't disclose any technical error data.
         */
        if (!$exception instanceof BaseException) {
            if (app()->environment('production')) {
                $data['message'] = Payload::$defaults[Payload::ARG_MESSAGE];
            }

            if (config('app.debug') === true) {
                return parent::render($request, $exception);
            }
        }

        $response = responder()->error($data['code'] ?? Payload::$defaults[Payload::ARG_IDENTIFIER], $data['message']);

        /**
         * "meta" is the extra errors (such as validation errors)
         * This is for a more detailed description of the occurred errors.
         */
        if ($data['meta'] && is_array($data['meta'])) {
            $response->data($data['meta']);
        }

        return $response->respond($data['statusCode'] ?? Payload::$defaults[Payload::ARG_STATUS_CODE]);
    }
}
