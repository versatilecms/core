<?php

namespace Versatile\Core\Policies;

use Versatile\Core\Contracts\UserInterface;

class UserPolicy extends BasePolicy
{
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
        // Does this record belong to the current user?
        $current = $user->id === $model->id;

        return $current || $this->checkPermission($user, $model, 'read');
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
        // Does this record belong to the current user?
        $current = $user->id === $model->id;

        return $current || $this->checkPermission($user, $model, 'edit');
    }

    /**
     * Determine if the given user can change a user a role.
     *
     * @param \Versatile\Core\Contracts\UserInterface $user
     * @param  $model
     *
     * @return bool
     */
    public function editRoles(UserInterface $user, $model)
    {
        // Does this record belong to another user?
        $another = $user->id != $model->id;

        return $another && $user->hasPermission('edit_users');
    }

    /**
     * Determine if the given model can be impersonated by the user.
     *
     * @param \Versatile\Core\Contracts\UserInterface $user
     * @param  $model
     *
     * @return bool
     */
    public function impersonate(UserInterface $user, $model)
    {
        // Does this record belong to the current user?
        if($user->id === $model->id) {
            return false;
        }

        return $this->checkPermission($user, $model, 'impersonate');
    }
}
