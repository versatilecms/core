<?php

use Versatile\Core\Seeders\AbstractBreadSeeder;

class DefaultBread extends AbstractBreadSeeder
{
    public function permissions()
    {
        return [
            [
                'name' => 'browse_admin',
                'description' => null,
                'table_name' => null,
                'roles' => ['admin']
            ],
            [
                'name' => 'browse_bread',
                'description' => null,
                'table_name' => null,
                'roles' => ['admin']
            ],
            [
                'name' => 'browse_database',
                'description' => null,
                'table_name' => null,
                'roles' => ['admin']
            ],
            [
                'name' => 'browse_media',
                'description' => null,
                'table_name' => null,
                'roles' => ['admin']
            ],
            [
                'name' => 'browse_compass',
                'description' => null,
                'table_name' => null,
                'roles' => ['admin']
            ],
        ];
    }
}
