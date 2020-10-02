<?php

namespace IgnitionWolf\API\Controllers;

use IgnitionWolf\API\Traits\Bounces;
use Flugg\Responder\Facades\Responder;
use Flugg\Responder\Http\Responses\SuccessResponseBuilder;
use Flugg\Responder\Transformers\Transformer;
use IgnitionWolf\API\Services\RequestValidator;
use Illuminate\Routing\Controller;
use League\Fractal\Resource\Item;
use Illuminate\Database\Eloquent\Model;
use IgnitionWolf\API\Entity\Model as IgnitionWolfModel;

class BaseController extends Controller
{
    use Bounces;

    /**
     * Wrapper function to return a successful response.
     *
     * @param null|array|Model|IgnitionWolfModel $data
     * @param Transformer|array $transformer
     * @return SuccessResponseBuilder
     */
    public function success($data = null, $transformer = null)
    {
        return responder()->success($data ?? [], $transformer)->respond();
    }
}
