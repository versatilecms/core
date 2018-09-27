<?php

namespace Versatile\Core\Bread\Traits;

trait Search
{
    /**
     * Checks if Model is searchable
     *
     * @return bool
     */
    public function isSearchable()
    {
        return property_exists($this->model, 'searchable');
    }
}
