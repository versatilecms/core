<?php

use App\User;
use Versatile\Core\Seeders\AbstractBreadSeeder;

class UsersBread extends AbstractBreadSeeder
{
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
        $faker = \Faker\Factory::create();

        if (User::count() == 0) {
            User::insert([
                'name' => 'Admin',
                'email' => 'admin@admin.com',
                'password' => bcrypt('123456'),
                'remember_token' => str_random(60),
                'role_id' => 1,
                'created_at' => $faker->dateTime()
            ]);
        }
    }
}
