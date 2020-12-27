<?php

namespace IgnitionWolf\API\Http\Controllers;

use IgnitionWolf\API\Traits\Bounces;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Database\Eloquent\Model;
use IgnitionWolf\API\Entity\Model as IgnitionWolfModel;

class BaseController extends Controller
{
    use Bounces;

    /**
     * Wrapper function to return a successful response.
     *
     * @param null|object|array|Model|IgnitionWolfModel $data
     * @param class-string $transformer
     * @return JsonResponse
     */
    public function success($data = null, $transformer = null): JsonResponse
    {
        return responder()->success($data ?? [], $transformer)->respond();
    }

    /**
     * Wrapper function to return a response with error.
     *
     * @param string $code
     * @param null|array|Model|IgnitionWolfModel $data
     * @return JsonResponse
     */
    public function error($code = '', $data = []): JsonResponse
    {
        return responder()->error($code, $data ?? [])->respond();
    }
}
