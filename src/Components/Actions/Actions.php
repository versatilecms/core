<?php

namespace Versatile\Core\Components\Actions;

use Versatile\Core\Components\Actions\Handlers\DeleteAction;
use Versatile\Core\Components\Actions\Handlers\EditAction;
use Versatile\Core\Components\Actions\Handlers\ViewAction;

use Illuminate\Support\Collection;
use Exception;

class Actions
{
    /**
     * View type actions
     * Available: button-group and dropdown
     *
     * @var string
     */
    protected $type = 'button-group';

    /**
     * @var array
     */
    protected $actions = [
        'view' => ViewAction::class,
        'edit' => EditAction::class,
        'delete' => DeleteAction::class
    ];

    /**
     * Sets the display format
     *
     * @param string $type
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Returns the display format
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param $action
     * @return $this
     * @throws Exception
     */
    public function add($action)
    {
        if (!class_exists($action)) {
            throw new Exception('The action you are trying to add could not be found. Action: ' . $action);
        }

        /**
         * @var $instance AbstractAction
         */
        $instance = app($action, ['dataType' => null, 'data' => null]);
        $this->actions[$instance->getName()] = $action;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getAll()
    {
        return collect($this->actions);
    }
}
