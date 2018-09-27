<?php

namespace Versatile\Core\Bread\Traits;

use Versatile\Core\Facades\Actions as ActionsFacade;

trait Actions
{

    // const ACTIONS_DROPDOWN = 'dropdown';

    // const ACTIONS_BUTTON_GROUP = 'button-group';

    /**
     * @var null|string
     */
    protected $actionsFormat = null;

    public function setActionsFormat($actionsFormat)
    {
        $this->actionsFormat = $actionsFormat;

        return $this;
    }

    public function defineActionsFormat()
    {
        if (!is_null($this->actionsFormat)) {
            ActionsFacade::setType($this->actionsFormat);
        }

        return $this;        
    }

    public function addActions($actions)
    {
        foreach ($actions as $action) {
            ActionsFacade::add($action);
        }

        return $this;
    }

    public function addAction($action)
    {
        ActionsFacade::add($action);

        return $this;
    }

    public function getActions()
    {
        return ActionsFacade::getAll();
    }
}
