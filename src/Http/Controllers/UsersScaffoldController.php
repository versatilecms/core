<?php

namespace Versatile\Core\Http\Controllers;

use Illuminate\Http\Request;

use Versatile\Core\Facades\Versatile;

use Versatile\Core\Models\User;
use Versatile\Core\Policies\UserPolicy;
use Versatile\Core\Models\Role;

use Versatile\Core\Bread\DataTypeController;
use Versatile\Core\Bread\DataType;

use Versatile\Core\Components\Filters\Users\CreatedAtFilter;
use Versatile\Core\Components\Filters\Users\RoleFilter;
use Versatile\Core\Components\Filters\Users\RolesFilter;
use Versatile\Core\Components\Actions\Handlers\ImpersonateAction;

class UsersScaffoldController extends DataTypeController
{
	public function setup()
	{

		$this->bread->name = 'scaffold';
		$this->bread->slug = 'scaffold';

        $this->bread->setDisplayName('User', 'Users');
		$this->bread->setIcon('versatile-person');
		$this->bread->setModel(User::class);
		$this->bread->setPolicy(UserPolicy::class);

        $this->bread->setActionsFormat('dropdown');//DataType::ACTIONS_DROPDOWN);

        $this->bread->addAction(
            ImpersonateAction::class
        );

        $this->bread->addFilters([
            RoleFilter::class,
            RolesFilter::class,
            CreatedAtFilter::class
        ]);

        //$this->bread->setEditAddView('versatile::users.edit-add');

		$this->bread->addDataRows([
           [
                'field' => 'id',
                'type' => 'number',
                'display_name' => __('versatile::seeders.data_rows.id'),
                'required' => true,
                'browse' => true,
                'read' => false,
                'edit' => false,
                'add' => false,
                'delete' => false,
                'details' => [],
                //'order' => 1,
            ],

            [
                'field' => 'avatar',
                'type' => 'image',
                'display_name' => __('versatile::seeders.data_rows.avatar'),
                'required' => false,
                'browse' => true,
                'read' => true,
                'edit' => true,
                'add' => true,
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
                //'order' => 2,
            ],

            [
                'field' => 'name',
                'type' => 'text',
                'display_name' => __('versatile::seeders.data_rows.name'),
                'required' => true,
                'browse' => true,
                'read' => true,
                'edit' => true,
                'add' => true,
                'delete' => 1,
                'details' => [],
                //'order' => 3,
            ],

            [
                'field' => 'email',
                'type' => 'text',
                'display_name' => __('versatile::seeders.data_rows.email'),
                'required' => true,
                'browse' => true,
                'read' => true,
                'edit' => true,
                'add' => true,
                'delete' => 1,
                'details' => [],
                //'order' => 4,
            ],

            [
                'field' => 'password',
                'type' => 'password',
                'display_name' => __('versatile::seeders.data_rows.password'),
                'required' => true,
                'browse' => false,
                'read' => false,
                'edit' => true,
                'add' => true,
                'delete' => false,
                'details' => [],
                //'order' => 5,
            ],

            [
                'field' => 'remember_token',
                'type' => 'text',
                'display_name' => __('versatile::seeders.data_rows.remember_token'),
                'required' => false,
                'browse' => false,
                'read' => false,
                'edit' => false,
                'add' => false,
                'delete' => false,
                'details' => [],
                //'order' => 6,
            ],

            [
                'field' => 'created_at',
                'type' => 'timestamp',
                'display_name' => __('versatile::seeders.data_rows.created_at'),
                'required' => false,
                'browse' => true,
                'read' => true,
                'edit' => false,
                'add' => false,
                'delete' => false,
                'details' => [],
                //'order' => 7,
            ],

            [
                'field' => 'updated_at',
                'type' => 'timestamp',
                'display_name' => __('versatile::seeders.data_rows.updated_at'),
                'required' => false,
                'browse' => false,
                'read' => false,
                'edit' => false,
                'add' => false,
                'delete' => false,
                'details' => [],
                //'order' => 8,
            ],

            [
                'field' => 'user_belongsto_role_relationship',
                'type' => 'relationship',
                'display_name' => __('versatile::seeders.data_rows.role'),
                'required' => false,
                'browse' => true,
                'read' => true,
                'edit' => true,
                'add' => true,
                'delete' => false,
                'details' => [
                    'model' => Role::class,
                    'table' => 'roles',
                    'type' => 'belongsTo',
                    'column' => 'role_id',
                    'key' => 'id',
                    'label' => 'display_name',
                    'pivot_table' => 'roles',
                    'pivot' => '0',
                ],
                //'order' => 9,
            ],

            [
                'field' => 'user_belongstomany_role_relationship',
                'type' => 'relationship',
                'display_name' => __('versatile::seeders.data_rows.roles'),
                'required' => false,
                'browse' => true,
                'read' => true,
                'edit' => true,
                'add' => true,
                'delete' => false,
                'details' => [
                    'model' => Role::class,
                    'table' => 'roles',
                    'type' => 'belongsToMany',
                    'column' => 'id',
                    'key' => 'id',
                    'label' => 'display_name',
                    'pivot_table' => 'user_roles',
                    'pivot' => '1',
                    'taggable' => '0',
                ],
                //'order' => 10,
            ],

            [
                'field' => 'locale',
                'type' => 'select_dropdown',
                'display_name' => 'Locale',
                'required' => false,
                'browse' => true,
                'read' => true,
                'edit' => true,
                'add' => true,
                'delete' => false,
                'details' => [
                    'default' => config('app.locale', 'en'),
                    'options' => Versatile::getLocales()
                ],
                //'order' => 11,
            ],

            [
                'field' => 'settings',
                'type' => 'hidden',
                'display_name' => 'Settings',
                'required' => false,
                'browse' => false,
                'read' => false,
                'edit' => false,
                'add' => false,
                'delete' => false,
                'details' => [],
                //'order' => 12,
            ],

            [
                'field' => 'role_id',
                'type' => 'text',
                'display_name' => __('versatile::seeders.data_rows.role'),
                'required' => true,
                'browse' => true,
                'read' => true,
                'edit' => true,
                'add' => true,
                'delete' => 1,
                'details' => [],
                //'order' => 9,
            ]
        ]);

        //$this->bread->setAddView('versatile::bread.add');
	}

    // public function index(Request $request)
    // {
    //     dd($this->bread->getAddView);
    // }
}
