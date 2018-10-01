<?php

namespace Versatile\Core\Models;

use Versatile\Core\Traits\HasRelationships;

class Role extends BaseModel
{
    use HasRelationships;

    protected $guarded = [];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_roles')
                    ->select(app(User::class)->getTable().'.*')
                    ->union($this->hasMany(User::class))->getQuery();
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }
}
