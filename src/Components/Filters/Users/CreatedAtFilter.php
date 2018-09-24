<?php

namespace Versatile\Core\Components\Filters\Users;

use Illuminate\Database\Eloquent\Builder;
use Versatile\Core\Components\Filters\Filter;

use Versatile\Html\Bootstrap\Facades\Bootstrap;

class CreatedAtFilter extends Filter
{
    /**
     * The displayable name of the action.
     *
     * @return string
     */
    public function label()
    {
        return __('versatile::generic.created_at');
    }

    /**
     * Apply the filter to the given query.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param mixed $value
     * @param string $property
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function __invoke(Builder $query, $value, string $property): Builder
    {
        return $query->where('created_at', $value);
    }

    /**
     * @return string
     */
    public function display()
    {
        return Bootstrap::date($this->getInputFormName(), $this->label(), $this->getFilterData());
    }
}