<?php

namespace Versatile\Core\Seeders;

use Illuminate\Database\Seeder;
use Versatile\Core\Traits\BreadUnseeder;
use Versatile\Core\Contracts\BreadUnseederInterface;

abstract class AbstractBreadUnseeder extends Seeder implements BreadUnseederInterface
{
    use BreadUnseeder;

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
