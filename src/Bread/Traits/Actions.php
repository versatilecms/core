<?php

namespace Versatile\Core\Bread\Traits;

use Versatile\Core\Facades\Actions as ActionsFacade;

trait Actions
{
    /**
     * @var null|string
     */
    protected $actionsFormat = null;

    public function defineActionsFormat()
    {
        if (!is_null($this->actionsFormat)) {
            ActionsFacade::setType($this->actionsFormat);
        }
    }

    /**
     * Get the actions available for the resource.
     *
     * @return array
     */
    public function actions()
    {
        return [];
    }

    public function getActions()
    {
        foreach ($this->actions() as $action) {
            ActionsFacade::add($action);
        }

        return ActionsFacade::getAll();
    }
}
