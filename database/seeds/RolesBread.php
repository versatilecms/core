<?php

use Versatile\Core\Models\Role;
use Versatile\Core\Seeders\AbstractBreadSeeder;

class RolesBread extends AbstractBreadSeeder
{
    public function permissions()
    {
        return [
            [
                'name' => 'browse_roles',
                'description' => null,
                'table_name' => 'roles',
                'roles' => ['admin']
            ],
            [
                'name' => 'read_roles',
                'description' => null,
                'table_name' => 'roles',
                'roles' => ['admin']
            ],
            [
                'name' => 'edit_roles',
                'description' => null,
                'table_name' => 'roles',
                'roles' => ['admin']
            ],
            [
                'name' => 'add_roles',
                'description' => null,
                'table_name' => 'roles',
                'roles' => ['admin']
            ],
            [
                'name' => 'delete_roles',
                'description' => null,
                'table_name' => 'roles',
                'roles' => ['admin']
            ]
        ];
    }

    public function extras()
    {
        $role = Role::firstOrNew(['name' => 'admin']);
        if (!$role->exists) {
            $role->fill([
                'display_name' => __('versatile::seeders.roles.admin'),
            ])->save();
        }

        $role = Role::firstOrNew(['name' => 'user']);
        if (!$role->exists) {
            $role->fill([
                'display_name' => __('versatile::seeders.roles.user'),
            ])->save();
        }
    }
}
