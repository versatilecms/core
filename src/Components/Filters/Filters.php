<?php

namespace Versatile\Core\Components\Filters;

use Exception;

class Filters
{
    /**
     * @var array
     */
    protected $filters = [];

    /**
     * @param $filter
     * @return $this
     * @throws Exception
     */
    public function add($filter)
    {
        if (!class_exists($filter)) {
            throw new Exception('The filter you are trying to add could not be found. Filter: ' . $filter);
        }

        /**
         * @var $instance Filter
         */
        $instance = app($filter);
        $this->filters[$instance->getName()] = $filter;

        return $this;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getAll()
    {
        return collect($this->filters);
    }

    public function get($name)
    {

        if (isset($this->filters[$name])) {
            return $this->filters[$name];
        }

        return null;
    }
}
