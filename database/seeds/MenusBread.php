<?php

use Versatile\Core\Seeders\AbstractBreadSeeder;

class MenusBread extends AbstractBreadSeeder
{
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
                'name' => 'read_menus',
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
