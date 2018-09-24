<?php

use Versatile\Core\Models\Role;
use Versatile\Core\Seeders\AbstractBreadSeeder;

class RolesBread extends AbstractBreadSeeder
{
    public function bread()
    {
        return [
            'name' => 'roles',
            'slug' => 'roles',
            'display_name_singular' => __('versatile::seeders.data_types.role.singular'),
            'display_name_plural' => __('versatile::seeders.data_types.role.plural'),
            'icon' => 'versatile-lock',
            'model_name' => 'Versatile\\Core\\Models\\Role',
            'generate_permissions' => 1,
            'description' => '',
        ];
    }

    public function inputFields()
    {
        return [
            'id' => [
                'type' => 'number',
                'display_name' => __('versatile::seeders.data_rows.id'),
                'required' => 1,
                'browse' => 0,
                'read' => 0,
                'edit' => 0,
                'add' => 0,
                'delete' => 0,
                'details' => '',
                'order' => 1,
            ],

            'name' => [
                'type' => 'text',
                'display_name' => __('versatile::seeders.data_rows.name'),
                'required' => 1,
                'browse' => 1,
                'read' => 1,
                'edit' => 1,
                'add' => 1,
                'delete' => 1,
                'details' => '',
                'order' => 2,
            ],

            'display_name' => [
                'type' => 'text',
                'display_name' => __('versatile::seeders.data_rows.display_name'),
                'required' => 1,
                'browse' => 1,
                'read' => 1,
                'edit' => 1,
                'add' => 1,
                'delete' => 1,
                'details' => '',
                'order' => 3,
            ],

            'created_at' => [
                'type' => 'timestamp',
                'display_name' => __('versatile::seeders.data_rows.created_at'),
                'required' => 0,
                'browse' => 0,
                'read' => 0,
                'edit' => 0,
                'add' => 0,
                'delete' => 0,
                'details' => '',
                'order' => 4,
            ],

            'updated_at' => [
                'type' => 'timestamp',
                'display_name' => __('versatile::seeders.data_rows.updated_at'),
                'required' => 0,
                'browse' => 0,
                'read' => 0,
                'edit' => 0,
                'add' => 0,
                'delete' => 0,
                'details' => '',
                'order' => 5,
            ]
        ];
    }

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
