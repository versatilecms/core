<?php

namespace Versatile\Core\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Versatile\Core\Contracts\UserInterface;
use Versatile\Core\Facades\Versatile;

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
        if (!isset(self::$datatypes[get_class($model)])) {
            $dataType = Versatile::model('DataType');
            self::$datatypes[get_class($model)] = $dataType->where('model_name', get_class($model))->first();
        }

        $dataType = self::$datatypes[get_class($model)];

        return $user->hasPermission($action.'_'.$dataType->name);
    }
}
