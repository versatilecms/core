<?php

namespace Versatile\Core\Policies;

use Versatile\Core\Contracts\UserInterface;

class SettingPolicy extends BasePolicy
{
    /**
     * Determine if the given user can browse the model.
     *
     * @param \Versatile\Core\Contracts\UserInterface $user
     * @param  $model
     *
     * @return bool
     */
    public function browse(UserInterface $user, $model)
    {
        return $user->hasPermission('browse_settings');
    }

    /**
     * Determine if the given model can be viewed by the user.
     *
     * @param \Versatile\Core\Contracts\UserInterface $user
     * @param  $model
     *
     * @return bool
     */
    public function read(UserInterface $user, $model)
    {
        return $user->hasPermission('read_settings');
    }

    /**
     * Determine if the given model can be edited by the user.
     *
     * @param \Versatile\Core\Contracts\UserInterface $user
     * @param  $model
     *
     * @return bool
     */
    public function edit(UserInterface $user, $model)
    {
        return $user->hasPermission('edit_settings');
    }

    /**
     * Determine if the given user can create the model.
     *
     * @param \Versatile\Core\Contracts\UserInterface $user
     * @param  $model
     *
     * @return bool
     */
    public function add(UserInterface $user, $model)
    {
        return $user->hasPermission('add_settings');
    }

    /**
     * Determine if the given model can be deleted by the user.
     *
     * @param \Versatile\Core\Contracts\UserInterface $user
     * @param  $model
     *
     * @return bool
     */
    public function delete(UserInterface $user, $model)
    {
        return $user->hasPermission('delete_settings');
    }
}
