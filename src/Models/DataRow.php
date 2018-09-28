<?php

namespace Versatile\Core\Models;

use Versatile\Core\Models\BaseModel;
use Versatile\Core\Facades\Versatile;

class DataRow extends BaseModel
{
    protected $table = 'data_rows';

    protected $guarded = [];

    public $timestamps = false;

    protected $casts = [
        'details' => 'array',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function dataType()
    {
        return $this->belongsTo(Versatile::modelClass('DataType'));
    }

    public function rowBefore()
    {
        $previous = self::where('data_type_id', '=', $this->data_type_id)
            ->where('order', '=', ($this->order - 1))
            ->first();
        
        if (isset($previous->id)) {
            return $previous->field;
        }

        return '__first__';
    }

    public function relationshipField()
    {
        $options = $this->details;

        if (!isset($options->column)) {
           throw new \Exception("This detail does not exist in DataRow {$this->field}"); 
        }

        return $options->column;
    }

    /**
     * Check if this field is the current filter.
     *
     * @return bool True if this is the current filter, false otherwise
     */
    public function isCurrentSortField()
    {
        return isset($_GET['order_by']) && $_GET['order_by'] == $this->field;
    }

    /**
     * Build the URL to sort data type by this field.
     *
     * @return string Built URL
     */
    public function sortByUrl()
    {
        $params = $_GET;
        $isDesc = isset($params['sort_order']) && $params['sort_order'] != 'asc';
        if ($this->isCurrentSortField() && $isDesc) {
            $params['sort_order'] = 'asc';
        } else {
            $params['sort_order'] = 'desc';
        }
        $params['order_by'] = $this->field;

        return url()->current().'?'.http_build_query($params);
    }
}
