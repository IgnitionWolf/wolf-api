<?php

namespace Spatie\Skeleton\Controllers;

use Spatie\Skeleton\Traits\Bounces;
use Flugg\Responder\Facades\Responder;
use Flugg\Responder\Http\Responses\SuccessResponseBuilder;
use Flugg\Responder\Transformers\Transformer;
use Illuminate\Routing\Controller;
use League\Fractal\Resource\Item;

class BaseController extends Controller
{
    use Bounces;

    /**
     * Wrapper function to return a successful response.
     *
     * @param array $data
     * @param Transformer $transformer
     * @return SuccessResponseBuilder|JsonResponse
     */
    public function success($data = [], $transformer = null)
    {
        /* If it's an array then let's map it into a Fractal\Item */
        if (is_array($data)) {
            $data = new Item($data);
        }

        return Responder::success($data, $transformer);
    }
}
