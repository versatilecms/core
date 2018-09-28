<?php

namespace Versatile\Core\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Versatile\Core\Contracts\UserInterface;

class BasePolicy
{
    use HandlesAuthorization;

    protected static $datatypes = [];

    /**
     * Handle all requested permission checks.
     *
     * @param string $name
     * @param array  $arguments
     *
     * @return bool
     */
    public function __call($name, $arguments)
    {
        if (count($arguments) < 2) {
            throw new \InvalidArgumentException('not enough arguments');
        }
        /** @var \Versatile\Core\Contracts\UserInterface $user */
        $user = $arguments[0];

        /** @var $model */
        $model = $arguments[1];

        return $this->checkPermission($user, $model, $name);
    }

    /**
     * Check if user has an associated permission.
     *
     * @param \Versatile\Core\Contracts\UserInterface $user
     * @param object                      $model
     * @param string                      $action
     *
     * @return bool
     */
    protected function checkPermission(UserInterface $user, $model, $action)
    {
        return $user->hasPermission($action.'_'.$model->getTable());
    }
}
