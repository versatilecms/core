<?php

use App\User;
use Versatile\Core\Models\Role;
use Versatile\Core\Seeders\AbstractBreadSeeder;

class UsersBread extends AbstractBreadSeeder
{
    public function bread()
    {
        return [
            'name' => 'users',
            'slug' => 'users',
            'display_name_singular' => __('versatile::seeders.data_types.user.singular'),
            'display_name_plural' => __('versatile::seeders.data_types.user.plural'),
            'icon' => 'versatile-person',
            'model_name' => 'Versatile\\Core\\Models\\User',
            'policy_name' => 'Versatile\\Core\\Policies\\UserPolicy',
            'controller' => '\\Versatile\\Core\\Http\\Controllers\\UsersController',
            'generate_permissions' => 1
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

            'avatar' => [
                'type' => 'image',
                'display_name' => __('versatile::seeders.data_rows.avatar'),
                'required' => 0,
                'browse' => 1,
                'read' => 1,
                'edit' => 1,
                'add' => 1,
                'delete' => 1,
                'details' => [
                    'resize' => [
                        'width' => '350',
                        'height' => '350',
                    ],
                    'quality' => '70%',
                    'upsize' => true,
                    'thumbnails' => [
                        [
                            'name' => 'medium',
                            'scale' => '50%',
                        ],
                        [
                            'name' => 'small',
                            'scale' => '25%',
                        ],
                        [
                            'name' => 'cropped',
                            'crop' => [
                                'width' => '250',
                                'height' => '250',
                            ],
                        ],
                    ],
                ],
                'order' => 2,
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
                'order' => 3,
            ],

            'email' => [
                'type' => 'text',
                'display_name' => __('versatile::seeders.data_rows.email'),
                'required' => 1,
                'browse' => 1,
                'read' => 1,
                'edit' => 1,
                'add' => 1,
                'delete' => 1,
                'details' => '',
                'order' => 4,
            ],

            'password' => [
                'type' => 'password',
                'display_name' => __('versatile::seeders.data_rows.password'),
                'required' => 1,
                'browse' => 0,
                'read' => 0,
                'edit' => 1,
                'add' => 1,
                'delete' => 0,
                'details' => '',
                'order' => 5,
            ],

            'remember_token' => [
                'type' => 'text',
                'display_name' => __('versatile::seeders.data_rows.remember_token'),
                'required' => 0,
                'browse' => 0,
                'read' => 0,
                'edit' => 0,
                'add' => 0,
                'delete' => 0,
                'details' => '',
                'order' => 6,
            ],

            'created_at' => [
                'type' => 'timestamp',
                'display_name' => __('versatile::seeders.data_rows.created_at'),
                'required' => 0,
                'browse' => 1,
                'read' => 1,
                'edit' => 0,
                'add' => 0,
                'delete' => 0,
                'details' => '',
                'order' => 7,
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
                'order' => 8,
            ],

            'user_belongsto_role_relationship' => [
                'type' => 'relationship',
                'display_name' => __('versatile::seeders.data_rows.role'),
                'required' => 0,
                'browse' => 1,
                'read' => 1,
                'edit' => 1,
                'add' => 1,
                'delete' => 0,
                'details' => [
                    'model' => 'Versatile\\Core\\Models\\Role',
                    'table' => 'roles',
                    'type' => 'belongsTo',
                    'column' => 'role_id',
                    'key' => 'id',
                    'label' => 'display_name',
                    'pivot_table' => 'roles',
                    'pivot' => '0',
                ],
                'order' => 9,
            ],

            'user_belongstomany_role_relationship' => [
                'type' => 'relationship',
                'display_name' => __('versatile::seeders.data_rows.roles'),
                'required' => 0,
                'browse' => 1,
                'read' => 1,
                'edit' => 1,
                'add' => 1,
                'delete' => 0,
                'details' => [
                    'model' => 'Versatile\\Core\\Models\\Role',
                    'table' => 'roles',
                    'type' => 'belongsToMany',
                    'column' => 'id',
                    'key' => 'id',
                    'label' => 'display_name',
                    'pivot_table' => 'user_roles',
                    'pivot' => '1',
                    'taggable' => '0',
                ],
                'order' => 10,
            ],

            'locale' => [
                'type' => 'text',
                'display_name' => 'Locale',
                'required' => 0,
                'browse' => 1,
                'read' => 1,
                'edit' => 1,
                'add' => 1,
                'delete' => 0,
                'details' => '',
                'order' => 11,
            ],

            'settings' => [
                'type' => 'hidden',
                'display_name' => 'Settings',
                'required' => 0,
                'browse' => 0,
                'read' => 0,
                'edit' => 0,
                'add' => 0,
                'delete' => 0,
                'details' => '',
                'order' => 12,
            ],

            'role_id' => [
                'type' => 'text',
                'display_name' => __('versatile::seeders.data_rows.role'),
                'required' => 1,
                'browse' => 1,
                'read' => 1,
                'edit' => 1,
                'add' => 1,
                'delete' => 1,
                'details' => '',
                'order' => 9,
            ]
        ];
    }

    public function permissions()
    {
        return [
            [
                'name' => 'browse_users',
                'description' => null,
                'table_name' => 'users',
                'roles' => ['admin']
            ],
            [
                'name' => 'read_users',
                'description' => null,
                'table_name' => 'users',
                'roles' => ['admin']
            ],
            [
                'name' => 'edit_users',
                'description' => null,
                'table_name' => 'users',
                'roles' => ['admin']
            ],
            [
                'name' => 'add_users',
                'description' => null,
                'table_name' => 'users',
                'roles' => ['admin']
            ],
            [
                'name' => 'delete_users',
                'description' => null,
                'table_name' => 'users',
                'roles' => ['admin']
            ],
            [
                'name' => 'impersonate_users',
                'description' => null,
                'table_name' => 'users',
                'roles' => ['admin']
            ]
        ];
    }

    public function extras()
    {
        if (User::count() == 0) {
            User::insert([
                'name' => 'Admin',
                'email' => 'admin@admin.com',
                'password' => bcrypt('123456'),
                'remember_token' => str_random(60),
                'role_id' => 1,
            ]);

            User::insert([
                'name' => 'Demo',
                'email' => 'demo@demo.com',
                'password' => bcrypt('123456'),
                'remember_token' => str_random(60),
                'role_id' => 1,
            ]);

            for ($i=0; $i < 100; $i++) { 
                User::insert([
                    'name' => 'User Demo - ' . $i,
                    'email' => 'demo' . $i . '@demo.com',
                    'password' => bcrypt('123456'),
                    'remember_token' => str_random(60),
                    'role_id' => 2,
                ]);
            }
        }
    }
}
