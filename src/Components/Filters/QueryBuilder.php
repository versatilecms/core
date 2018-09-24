<?php

namespace Versatile\Core\Components\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Versatile\QueryBuilder\Filter;
use Versatile\QueryBuilder\QueryBuilder as QueryBuilderBase;

class QueryBuilder
{
    /**
     * @var Builder
     */
    protected $builder;

    /**
     * @var QueryBuilderBase
     */
    protected $queryBuilder;

    public function __construct(Builder $builder, Request $request)
    {
        $this->builder = $builder;

        $this->queryBuilder = QueryBuilderBase::for($this->builder, $request);
    }

    public static function for($baseQuery, $request) : self
    {
        if (is_string($baseQuery)) {
            $baseQuery = ($baseQuery)::query();
        }

        return new static($baseQuery, $request);
    }


    /**
     * Apply filters
     *
     * @param array $filters
     * @return Builder
     */

    public function apply($filters)
    {
        $allowed = [];
        foreach ($filters as $filterProperty => $filterClass) {
            $allowed[] = Filter::custom($filterProperty, $filterClass);

        }

        return $this->queryBuilder
                ->allowedFilters($allowed);
    }
}
