<?php

namespace Versatile\Core\Http\Controllers\Traits;

use Versatile\Core\Facades\Actions as ActionsFacade;

trait Actions
{
    public function defineActionsFormat()
    {
        if (!is_null($this->actionsFormat)) {
            ActionsFacade::setType($this->actionsFormat);
        }
    }

    public function getActions()
    {
        foreach ($this->actions() as $action) {
            ActionsFacade::add($action);
        }

        return ActionsFacade::getAll();
    }
}
