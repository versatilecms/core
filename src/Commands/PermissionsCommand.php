<?php

namespace Versatile\Core\Commands;

use Illuminate\Console\Command;
use Versatile\Core\Facades\Versatile;

class PermissionsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'versatile:permissions {role=admin}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enables all permissions for the role';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $role = Versatile::model('Role')
            ->where('name', $this->argument('role'))
            ->first();

        if (is_null($role)) {
            exit('Role not found');
        }

        // Get all permissions
        $permissions = Versatile::model('Permission')->all();

        // Assign all permissions to the admin role
        $role->permissions()->sync(
            $permissions->pluck('id')->all()
        );

        $this->info('All permissions were granted for the role');
    }
}
