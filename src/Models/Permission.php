<?php

namespace Versatile\Core\Models;

use Versatile\Core\Traits\HasRelationships;

class Permission extends BaseModel
{
    use HasRelationships;

    protected $guarded = [];

    public function roles()
    {
        return $this->hasMany(Role::class);
    }

    public static function generateFor($table_name)
    {
        self::firstOrCreate([
            'key' => 'browse_'.$table_name,
            'table_name' => $table_name
        ]);

        self::firstOrCreate([
            'key' => 'read_'.$table_name,
            'table_name' => $table_name
        ]);

        self::firstOrCreate([
            'key' => 'edit_'.$table_name,
            'table_name' => $table_name
        ]);

        self::firstOrCreate([
            'key' => 'add_'.$table_name,
            'table_name' => $table_name
        ]);

        self::firstOrCreate([
            'key' => 'delete_'.$table_name,
            'table_name' => $table_name
        ]);

        self::firstOrCreate([
            'key' => 'filter_'.$table_name,
            'table_name' => $table_name
        ]);
    }

    public static function removeFrom($table_name)
    {
        self::where(['table_name' => $table_name])->delete();
    }
}
