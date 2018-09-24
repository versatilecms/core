<?php

namespace Versatile\Core\Components\Filters;

use Illuminate\Database\Eloquent\Builder;

use Versatile\QueryBuilder\Filters\Filter as FilterInterface;

abstract class Filter implements FilterInterface
{
    /**
     * @var bool
     */
    public $display = true;

    /**
     * Detailed filter description
     *
     * @return null|string
     */
    public function description()
    {
        return null;
    }

    /**
     * Apply the filter to the given query.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param mixed $value
     * @param string $property
     * @return \Illuminate\Database\Eloquent\Builder
     */
    abstract public function __invoke(Builder $query, $value, string $property): Builder;

    /**
     * User mapping method.
     */
    public function display()
    {
    }

    /**
     * Get the class name and convert to snake_case
     * @return string
     */
    public function getName()
    {
        $name = class_basename($this);
        if (ends_with($name, 'Filter')) {
            $name = substr($name, 0, -strlen('Filter'));
        }

        return snake_case($name);
    }

    /**
     * @return string
     */
    public function getInputFormName()
    {
        return "filter[" . $this->getName() . "]";
    }

    public function getFilterData($default = null)
    {
        return request()->filters()->get($this->getName(), $default);
    }
}
