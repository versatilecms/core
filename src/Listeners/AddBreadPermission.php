<?php

namespace Versatile\Core\Listeners;

use Versatile\Core\Events\BreadAdded;
use Versatile\Core\Facades\Versatile;
use Versatile\Core\Models\Permission;
use Versatile\Core\Models\Role;

class AddBreadPermission
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Create Permission for a given BREAD.
     *
     * @param BreadAdded $event
     *
     * @return void
     */
    public function handle(BreadAdded $bread)
    {
        if (config('versatile.bread.add_permission') && file_exists(base_path('routes/web.php'))) {
            // Create permission
            $role = Role::where('name', config('versatile.bread.default_role'))->firstOrFail();

            // Get permission for added table
            $permissions = Permission::where(['table_name' => $bread->dataType->name])->get()->pluck('id')->all();

            // Assign permission to admin
            $role->permissions()->attach($permissions);
        }
    }
}
