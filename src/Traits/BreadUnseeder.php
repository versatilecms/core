<?php

namespace Versatile\Core\Traits;

// use Versatile\Core\Models\Menu;
// use Versatile\Core\Models\DataRow;
// use Versatile\Core\Models\DataType;
// use Versatile\Core\Models\MenuItem;
// use Versatile\Core\Models\Permission;
// use Versatile\Core\Models\Setting;
// use Versatile\Core\Models\Role;
// use DB;

trait BreadUnseeder
{
    protected $bread;
    protected $inputFields;
    protected $menu;
    protected $settings;
    protected $files;
    protected $permissions;

    public function __construct()
    {
        $this->bread = $this->bread();
        $this->inputFields = $this->inputFields();
        $this->menu = $this->menu();
        $this->settings = $this->settings();
        $this->files = $this->files();
        $this->permissions = $this->permissions();
    }

    /**
     * Run the database (un)seeders.
     *
     * @return void
     */
    public function run()
    {
        $this->deleteDataType();
        $this->deleteInputFields();
        $this->deleteMenuItem();
        $this->createSettings();
        $this->unpublishFiles();
        $this->deletePermissions();
        $this->extras();
    }

    public function deleteDataType()
    {
    }

    public function deleteInputFields()
    {
    }

    public function deleteMenuItem()
    {
    }

    public function deletePermissions()
    {
    }

    public function createSettings()
    {
    }

    public function unpublishFiles()
    {
    }
}
