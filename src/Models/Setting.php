<?php

namespace Versatile\Core\Models;

class Setting extends BaseModel
{
    protected $table = 'settings';

    protected $guarded = [];

    public $timestamps = false;

    protected $casts = [
        'details' => 'object',
    ];
}
