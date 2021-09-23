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
    const DEFAULT_HTTP_CODE = 500;
    const DEFAULT_PRETTY_CODE = 'INTERNAL_ERROR';

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
            'httpCode' => self::DEFAULT_HTTP_CODE,
            'message' => null,
            'prettyCode' => self::DEFAULT_PRETTY_CODE,
            'meta' => null
        ];

        /**
         * Here we are looking at three different types of exceptions:
         * BaseException (this package's exception)
         * HttpException (Laravel's exception)
         * Exception (PHP default exception)
         *
         * We'll prepare the data accordingly.
         */
        if ($exception instanceof BaseException) {
            $data['message'] = $exception->getMessage();
            $data['httpCode'] = $exception->getCode();
            $data['prettyCode'] = $exception->getPrettyCode();
            $data['meta'] = $exception->getMeta();
        } elseif ($exception instanceof HttpException) {
            $data['message'] = $exception->getMessage();
            $data['httpCode'] = $exception->getStatusCode() ?? $exception->getCode() ?? self::DEFAULT_HTTP_CODE;
            $data['prettyCode'] = self::DEFAULT_PRETTY_CODE;
        } else {
            $data['message'] = $exception->getMessage();
            $data['prettyCode'] = self::DEFAULT_PRETTY_CODE;

            if (($code = $exception->getCode()) && ($code >= 400 && $code <= 600)) {
                $data['httpCode'] = $code;
            }
        }

        /**
         * If we're in production or non-debug mode we shouldn't disclose any technical error data.
         */
        if (!$exception instanceof BaseException) {
            if (app()->environment('production')) {
                $data['message'] = trans('api::exceptions.default_error');
            }

            if (config('app.debug') === true) {
                return parent::render($request, $exception);
            }
        }

        $response = responder()->error($data['prettyCode'], $data['message']);

        /**
         * "meta" is the extra errors (such as validation errors)
         * This is for a more detailed description of the occurred errors.
         */
        if ($data['meta'] && is_array($data['meta'])) {
            $response->data($data['meta']);
        }

        return $response->respond($data['httpCode']);
    }
}
