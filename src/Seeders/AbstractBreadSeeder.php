<?php

namespace Versatile\Core\Seeders;

use Illuminate\Database\Seeder;
use Versatile\Core\Traits\BreadSeeder;
use Versatile\Core\Contracts\BreadSeederInterface;

abstract class AbstractBreadSeeder extends Seeder implements BreadSeederInterface
{
    use BreadSeeder;

    protected $forceUpdate = true;

    public function bread()
    {
        return null;
    }

    public function inputFields()
    {
        return null;
    }

    public function menu()
    {
        return null;
    }

    public function settings()
    {
        return null;
    }

    public function permissions()
    {
        return null;
    }

    public function files()
    {
        return null;
    }

    public function extras()
    {
    }
}
