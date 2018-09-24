<?php

namespace Versatile\Core\Components\Filters\Users;

use Illuminate\Database\Eloquent\Builder;
use Versatile\Core\Components\Filters\Filter;

use Versatile\Core\Models\Role;

use Versatile\Html\Bootstrap\Facades\Bootstrap;

class RoleFilter extends Filter
{
    /**
     * The displayable name of the action.
     *
     * @return string
     */
    public function label()
    {
        return __('versatile::profile.role_default');
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
        return $query->where('role_id', $value);
    }

    /**
     * @return string
     */
    public function display()
    {
        $roles = Role::all()->pluck('display_name', 'id');

        return Bootstrap::checkboxes($this->getInputFormName(), $this->label(), $roles, $this->getFilterData([]));
    }
}