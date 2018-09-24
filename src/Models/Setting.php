<?php

namespace Versatile\Core\Models;

use Versatile\Core\Models\BaseModel;

class Setting extends BaseModel
{
    protected $table = 'settings';

    protected $guarded = [];

    public $timestamps = false;
}
