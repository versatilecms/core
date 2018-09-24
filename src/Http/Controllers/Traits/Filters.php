<?php

namespace Versatile\Core\Http\Controllers\Traits;

use Versatile\Core\Facades\Filters as FiltersFacade;

trait Filters
{
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
