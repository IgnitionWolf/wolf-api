<?php


namespace IgnitionWolf\API\Strategies\Filter;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use ReflectionException;

class PostgreSQLFilterStrategy implements FilterStrategy
{
    /**
     * Filter using the PostgreSQL Scout driver package.
     *
     * The $filters structure is expected to look like this:
     * $filters => [
     *      'name' => ['john', 'maria'],
     *      'sku' => ['SKU-123'],
     * ]
     *
     * @url https://github.com/pmatseykanets/laravel-scout-postgres
     * @param array $filters
     * @param string $context
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
        ) && sizeof($filters)) {
            throw new Exception('
                The ' . $modelReflection->getShortName() . ' model needs the trait: Scout\Searchable.
            ');
        }

        /**
         * We will be using the Laravel Scout Postgres package syntax.
         * @url https://github.com/pmatseykanets/laravel-scout-postgres#usage
         */
        $searchQuery = '';
        foreach ($filters as $key => $filterSet) {
            if (strlen($searchQuery)) {
                $searchQuery .= ' & ';
            }

            if (count($filterSet) > 1) {
                $searchQuery .= "(";
                foreach ($filterSet as $idx => $filter) {
                    if ($idx > 0) {
                        $searchQuery .= " | $filter";
                    } else {
                        $searchQuery .= "$filter";
                    }
                }
                $searchQuery .= ")";
            } else {
                $searchQuery .= "$filterSet[0]";
            }
        }

        return $context::search($searchQuery)->usingTsQuery();
    }
}
