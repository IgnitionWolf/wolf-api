<?php

namespace IgnitionWolf\API\Strategies\Filter;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use ReflectionException;

class ElasticFilterStrategy implements FilterStrategy
{
    /**
     * Filter using the Elastic Cache package by babenkoivan.
     *
     * The $filters structure is expected to look like this:
     * $filters => [
     *      'name' => ['john', 'maria'],
     *      'sku' => ['SKU-123'],
     * ]
     *
     * @url https://github.com/babenkoivan/elastic-scout-driver-plus
     * @param array $filters
     * @param string $context class-string
     * @return Builder
     * @throws ReflectionException
     * @throws Exception
     */
    public function filter(array $filters, string $context)
    {
        $modelReflection = new \ReflectionClass($context);

        if (!in_array(
            Searchable::class,
            array_keys($modelReflection->getTraits())
        )) {
            throw new Exception('
                The ' . $modelReflection->getShortName() . ' model needs the trait: Scout\Searchable.
            ');
        }

        /**
         * We will be using the Elastic Search query string syntax
         * @url https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-query-string-query.html
         */
        $searchQuery = '';
        foreach ($filters as $key => $filterSet) {
            if (strlen($searchQuery)) {
                $searchQuery .= ' AND ';
            }

            if (count($filterSet) > 1) {
                $searchQuery .= "$key:(";
                foreach ($filterSet as $idx => $filter) {
                    if ($idx > 0) {
                        $searchQuery .= " OR $filter";
                    } else {
                        $searchQuery .= "$filter";
                    }
                }
                $searchQuery .= ")";
            } else {
                $searchQuery .= "$key:($filterSet[0])";
            }
        }

        return $context::search($searchQuery);
    }
}
