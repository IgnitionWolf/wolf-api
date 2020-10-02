<?php

namespace IgnitionWolf\API\Strategies\Filter;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use ReflectionException;

class ElasticCacheFilterStrategy implements FilterStrategy
{
    /**
     * Filter using the Elastic Cache Plus package by babenkoivan.
     *
     * @url https://github.com/babenkoivan/elastic-scout-driver-plus
     * @param Request $request
     * @param Builder $context
     * @return Builder
     * @throws ReflectionException
     * @throws Exception
     */
    public function filter(Request $request, $context)
    {
        $modelReflection = new \ReflectionClass($context);

//        if (!in_array(
//            'ElasticScoutDriverPlus\CustomSearch',
//            array_keys($modelReflection->getTraits())
//        )) {
//            throw new Exception('
//                The ' . $modelReflection->getShortName() . ' model needs the trait: ElasticScoutDriverPlus\CustomSearch.
//            ');
//        }

        $searchQuery = '';
        foreach ($request->get('filters', []) as $key => $filter) {
            $searchQuery = $searchQuery . "$key:($filter)";
        }

        return $context::search($searchQuery);
    }
}
