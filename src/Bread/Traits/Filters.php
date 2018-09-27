<?php

namespace Versatile\Core\Bread\Traits;

use Versatile\Core\Facades\Filters as FiltersFacade;

trait Filters
{
    public function addFilters($filters)
    {
        foreach ($filters as $filter) {
            FiltersFacade::add($filter);
        }

        return $this;
    }

    public function addFilter($filter)
    {
        FiltersFacade::add($filter);

        return $this;
    }

    /**
     * @return \Illuminate\Support\Collection|\Tightenco\Collect\Support\Collection
     */
    public function getFilters()
    {
        return FiltersFacade::getAll();
    }
}