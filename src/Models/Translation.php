<?php

namespace Versatile\Core\Models;

class Translation extends BaseModel
{
    protected $table = 'translations';

    protected $fillable = [
    	'table_name',
    	'column_name',
    	'foreign_key',
    	'locale',
    	'value'
    ];
}
