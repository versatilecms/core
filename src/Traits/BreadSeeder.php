<?php

namespace Versatile\Core\Traits;

use Versatile\Core\Models\Menu;
use Versatile\Core\Models\DataRow;
use Versatile\Core\Models\DataType;
use Versatile\Core\Models\MenuItem;
use Versatile\Core\Models\Permission;
use Versatile\Core\Models\Setting;
use Versatile\Core\Models\Role;
use DB;

trait BreadSeeder
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
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->createDataType();
        $this->createInputFields();
        $this->createMenuItem();
        $this->createSettings();
        $this->publishFiles();
        $this->generatePermissions();
        $this->extras();
    }

    /**
     * Create a new data-type for the current bread
     *
     * @return void
     */
    public function createDataType()
    {
        if (is_null($this->bread)) {
            return;
        }

        $dataType = DataType::where('name', $this->bread['name'])->first();

        if (is_null($dataType)) {
            DataType::create($this->bread);
        }

        if (!is_null($dataType) && $this->forceUpdate) {
            $dataType->update($this->bread);
        }
    }

    /**
     * Create all the input fields specified in the
     * bread() method
     *
     * @return [type] [description]
     */
    public function createInputFields()
    {
        $fields = [
            'data_type_id',
            'field',
            'type',
            'display_name',
            'required',
            'browse',
            'read',
            'edit',
            'add',
            'delete',
            'filter',
            'details',
            'order'
        ];

        if (is_null($this->bread) || is_null($this->inputFields)) {
            return;
        }

        $dataType = DataType::where('name', $this->bread['name'])->first();

        if (is_null($dataType)) {
            return;
        }

        collect($this->inputFields)->each(function ($field, $key) use ($dataType, $fields) {

            $dataRow = DataRow::where('data_type_id', $dataType->id)
                ->where('field', $key)
                ->first();

                $field['data_type_id'] = $dataType->id;
                $field['field'] = $key;

                if (is_array($field['details'])) {
                    $field['details'] = json_encode($field['details']);
                }

                $field = collect($field)->only($fields)->toArray();

            if (is_null($dataRow)) {
                DataRow::create($field);
            }

            if (!is_null($dataRow) && $this->forceUpdate) {
                $dataRow->update($field);
            }
        });

    }

    /**
     * Create the new menu entry using the configuration
     * specified in the menu() method. IF set to null
     * then no menu entry is going to be created
     *
     * @return [type] [description]
     */
    public function createMenuItem()
    {
        $fields = [
            'menu_id',
            'title',
            'url',
            'target',
            'icon_class',
            'color',
            'parent_id',
            'order',
            'route',
            'parameters'
        ];

        if (is_null($this->menu)) {
            return;
        }

        foreach ($this->menu as $key => $item) {

            $role = $item['role'];

            $children = false;
            if (isset($item['children']) && !empty($item['children']) && is_array($item['children'])) {
                $children = $item['children'];
            }

            $menu = Menu::where('name', $role)->first();

            if (is_null($menu)) {
                $menu = Menu::create(['name' => $role]);
            }

            $item = collect($item)->only($fields)->toArray();

            $item['menu_id'] = $menu->id;

            if (!isset($item['order']) || empty($item['order'])) {
                $item['order'] = $key+1;
            }

            if (!isset($item['target']) || empty($item['target'])) {
                $item['target'] = '_self';
            }

            $menuItem = MenuItem::create($item);

            if ($children) {
                foreach ($children as $k => $child) {

                    $child['menu_id'] = $menu->id;
                    $child['parent_id'] = $menuItem->id;

                    if (!isset($child['order']) || empty($child['order'])) {
                        $child['order'] = $k+1;
                    }

                    $child = collect($child)->only($fields)->toArray();
                    MenuItem::create($child);
                }
            }
        }
    }

    /**
     * Generates admin permissions to the current
     * bread
     *
     * @return void
     */
    public function generatePermissions()
    {
        if (is_null($this->permissions)) {

            if (
                isset($this->bread['generate_permissions']) &&
                $this->bread['generate_permissions']
            ) {
                Permission::generateFor($this->bread['name']);
            }

            return;
        }

        foreach ($this->permissions as $permission) {
            $this->insertPermissions($permission);
        }

    }

    /**
     * Dynamically insert permissions
     *
     * @param $permission
     */
    protected function insertPermissions($data)
    {
        $permission = Permission::where('key', $data['name'])
            ->where('table_name', $data['table_name'])
            ->first();

        if (is_null($permission)) {
            $permission = Permission::create([
                'key' => $data['name'],
                'table_name' => $data['table_name']
            ]);
        }

        if (isset($data['roles']) && !empty($data['roles']) && is_array($data['roles'])) {
            $roles = Role::whereIn('name', $data['roles'])
                ->get();

            if ($roles) {
                foreach ($roles as $role) {

                    $check = DB::table('permission_role')
                        ->where('permission_id', $permission->id)
                        ->where('role_id', $role->id)
                        ->exists();

                    if (!$check) {
                        DB::table('permission_role')->insert([
                            'permission_id' => $permission->id,
                            'role_id' => $role->id
                        ]);
                    }
                }
            }
        }
    }

    public function publishFiles()
    {
    }

    /**
     * Dynamically insert settings
     */
    public function createSettings()
    {
        $fields = [
            'key',
            'display_name',
            'value',
            'details',
            'type',
            'order',
            'group'
        ];

        if (is_null($this->settings)) {
            return;
        }

        foreach ($this->settings as $value) {

            $setting = Setting::where('key', $value['key'])->first();

            $value['key'] = $value['key'];
            $value = collect($value)->only($fields)->toArray();

            if (is_null($setting)) {
                $setting = Setting::create($value);
            }

            if (!is_null($setting) && $this->forceUpdate) {
                $setting->update($value);
            }
        };
    }
}
