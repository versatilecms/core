<?php

use Versatile\Core\Seeders\AbstractBreadSeeder;

class MenusBread extends AbstractBreadSeeder
{
    public function bread()
    {
        return [
            'name' => 'menus',
            'slug' => 'menus',
            'display_name_singular' => __('versatile::seeders.data_types.menu.singular'),
            'display_name_plural' => __('versatile::seeders.data_types.menu.plural'),
            'icon' => 'versatile-list',
            'model_name' => 'Versatile\\Core\\Models\\Menu',
            'generate_permissions' => 1,
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
                'order' => 3,
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
                'order' => 4,
            ]
        ];
    }

    public function menu()
    {
        return [
            [
                'role' => 'admin',
                'title' => __('versatile::seeders.menu_items.dashboard'),
                'route' => 'versatile.dashboard',
                'icon_class' => 'versatile-boat',
                'order' => 1,
            ],
            [
                'role' => 'admin',
                'title' => __('versatile::seeders.menu_items.roles'),
                'route' => 'versatile.roles.index',
                'icon_class' => 'versatile-lock',
                'order' => 2,
            ],
            [
                'role' => 'admin',
                'title' => __('versatile::seeders.menu_items.users'),
                'route' => 'versatile.users.index',
                'icon_class' => 'versatile-person',
                'order' => 3,
            ],
            [
                'role' => 'admin',
                'title' => __('versatile::seeders.menu_items.media'),
                'route' => 'versatile.media.index',
                'icon_class' => 'versatile-images',
                'order' => 5,
            ],
            [
                'role' => 'admin',
                'title' => __('versatile::seeders.menu_items.tools'),
                'icon_class' => 'versatile-tools',
                'order' => 9,
                'children' => [
                    [
                        'title' => __('versatile::seeders.menu_items.menu_builder'),
                        'route' => 'versatile.menus.index',
                        'icon_class' => 'versatile-list',
                        'order' => 10,
                    ],
                    [
                        'title' => __('versatile::seeders.menu_items.database'),
                        'route' => 'versatile.database.index',
                        'icon_class' => 'versatile-data',
                        'order' => 11,
                    ],
                    [
                        'title' => __('versatile::seeders.menu_items.compass'),
                        'route' => 'versatile.compass.index',
                        'icon_class' => 'versatile-compass',
                        'order' => 12,
                    ],
                    [
                        'title' => __('versatile::seeders.menu_items.bread'),
                        'route' => 'versatile.bread.index',
                        'icon_class' => 'versatile-bread',
                        'order' => 13,
                    ]
                ]
            ],
            [
                'role' => 'admin',
                'title' => __('versatile::seeders.menu_items.settings'),
                'route' => 'versatile.settings.index',
                'icon_class' => 'versatile-settings',
                'order' => 14,
            ]
        ];
    }

    public function permissions()
    {
        return [
            [
                'name' => 'browse_menus',
                'description' => null,
                'table_name' => 'menus',
                'roles' => ['admin']
            ],
            [
                'name' => 'edit_menus',
                'description' => null,
                'table_name' => 'menus',
                'roles' => ['admin']
            ],
            [
                'name' => 'add_menus',
                'description' => null,
                'table_name' => 'menus',
                'roles' => ['admin']
            ],
            [
                'name' => 'delete_menus',
                'description' => null,
                'table_name' => 'menus',
                'roles' => ['admin']
            ]
        ];
    }
}
