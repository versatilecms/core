<?php

namespace Versatile\Core\Models;

use Versatile\Core\Models\BaseModel;
use Versatile\Core\Facades\Versatile;
use Versatile\Core\Traits\HasRelationships;

class Role extends BaseModel
{
    use HasRelationships;

    protected $guarded = [];

    public function users()
    {
        $userModel = Versatile::modelClass('User');

        return $this->belongsToMany($userModel, 'user_roles')
                    ->select(app($userModel)->getTable().'.*')
                    ->union($this->hasMany($userModel))->getQuery();
    }

    public function permissions()
    {
        return $this->belongsToMany(Versatile::modelClass('Permission'));
    }
}
