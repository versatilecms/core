<?php

namespace Versatile\Core\Bread\Traits;

use Versatile\Core\Facades\Filters as FiltersFacade;

trait Filters
{
    /**
     * Get the filters available for the resource.
     *
     * @return array
     */
    public function filters()
    {
        return [];
    }

    /**
     * @return \Illuminate\Support\Collection|\Tightenco\Collect\Support\Collection
     */
    public function getFilters()
    {
        foreach ($this->filters() as $filter) {
            FiltersFacade::add($filter);
        }

        return FiltersFacade::getAll();
    }
}