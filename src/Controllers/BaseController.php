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
        /* If it's an array then let's convert it into a Fractal\Item */
        if (is_array($data)) {
            $data = new Item($data);
        }

        /**
         * Load the transfomer magically if not provided one.
         */
        if ($data instanceof Model && !$transformer) {
            try {
                $shortName = (new \ReflectionClass($data))->getShortName();
                $find = RequestValidator::getNamespace(get_class($data)) . "\\Transformers\\{$shortName}Transformer";
                if (class_exists($find)) {
                    $transformer = $find;
                }
            } catch (\Exception $e) {
                //
            }
        }

        return Responder::success($data ?? [], $transformer);
    }
}
