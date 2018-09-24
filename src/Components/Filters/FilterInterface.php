<?php

namespace Versatile\Core\Components\Filters;

use Illuminate\Database\Eloquent\Builder;

interface FilterInterface
{

    /**
     * Filter label
     *
     * @return string
     */
    public function label();

    /**
     * Apply the filter to the given query.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param mixed $value
     * @param string $property
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function __invoke(Builder $query, $value, string $property): Builder;

    /**
     * @return mixed
     */
    public function display();
}
