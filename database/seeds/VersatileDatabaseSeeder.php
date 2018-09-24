<?php

use Illuminate\Database\Seeder;
use Versatile\Core\Traits\Seedable;

class VersatileDatabaseSeeder extends Seeder
{
    use Seedable;

    protected $seedersPath = __DIR__ . '/';

    public function run()
    {
        $this->seed('RolesBread');
        $this->seed('DefaultBread');
        $this->seed('MenusBread');
        $this->seed('SettingsBread');
        // $this->seed('TranslationsTableSeeder');
        $this->seed('UsersBread');
    }
}
