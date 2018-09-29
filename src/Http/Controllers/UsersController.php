<?php

namespace Versatile\Core\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

use Versatile\Core\Facades\Versatile;

use Versatile\Core\Models\User;
use Versatile\Core\Policies\UserPolicy;
use Versatile\Core\Models\Role;

use Versatile\Core\Components\Filters\Users\CreatedAtFilter;
use Versatile\Core\Components\Filters\Users\RoleFilter;
use Versatile\Core\Components\Filters\Users\RolesFilter;
use Versatile\Core\Components\Actions\Handlers\ImpersonateAction;

class UsersController extends BaseController
{
    /**
     * Informs if DataType will be loaded from the database or setup
     *
     * @var bool
     */
    protected $dataTypeFromDatabase = false;

	public function setup()
	{
		$this->bread->setName('users');
		$this->bread->setSlug ('users');

        $this->bread->setDisplayNameSingular(__('versatile::seeders.data_types.user.singular'));
        $this->bread->setDisplayNamePlural(__('versatile::seeders.data_types.user.plural'));

		$this->bread->setIcon('versatile-person');
		$this->bread->setModel(User::class);
		$this->bread->addPolicy(User::class, UserPolicy::class);

        $this->bread->setActionsFormat('dropdown');//DataType::ACTIONS_DROPDOWN);

        $this->bread->addAction(ImpersonateAction::class);

        $this->bread->addFilters([
            RoleFilter::class,
            RolesFilter::class,
            CreatedAtFilter::class
        ]);

        $this->bread->setEditAddView('versatile::users.edit-add');

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
                'delete' => true,
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
                'delete' => true,
                'details' => [],
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
                'delete' => true,
                'details' => [],
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
            ],
//            [
//                'field' => 'role_id',
//                'type' => 'text',
//                'display_name' => __('versatile::seeders.data_rows.role'),
//                'required' => true,
//                'browse' => true,
//                'read' => true,
//                'edit' => true,
//                'add' => true,
//                'delete' => true,
//                'details' => [],
///            ],
            [
                'field' => 'role_id',
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
                    'pivot' => 0
                ],
            ],

            [
                'field' => 'user_roles',
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
                    'pivot' => 1,
                    'taggable' => 0,
                ],
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
            ],
        ]);
	}

    /**
     * Impersonate a user as an administrator
     *
     * @param $userId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function impersonate($userId)
    {
        // Store our current 'admin' id to switch back to
        Session::put('original_user.name', Auth::user()->name);
        Session::put('original_user.id', Auth::id());

        // Impersonate the requested user
        Auth::loginUsingId($userId);

        return redirect()->route('versatile.dashboard');
    }

    /**
     * Login as the original user and destroy session
     *
     * @param $userId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function revertImpersonate($userId)
    {
        if (Session::has('original_user.id') && $userId == Session::get('original_user.id')) {
            // Login as the original user and destroy session
            Session::forget('original_user');
            Auth::loginUsingId($userId);

            return redirect()->route('versatile.users.index');
        }
    }
}
