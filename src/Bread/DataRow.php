<?php

namespace Versatile\Core\Bread;

class DataRow
{
    public $field;
    public $type;
    public $display_name;
    public $required;
    public $browse;
    public $read;
    public $edit;
    public $add;
    public $delete;
    public $details;
    public $order;

    public $dataType;

    public function __construct($dataRow)
    {
        foreach ($dataRow as $property => $value) {

            if (!property_exists($this, $property)) {
                throw new \Exception("This property {$property} does not exist");
            }

            if ($property == 'details' && is_array($value)) {
                $value = json_decode(json_encode($value, JSON_FORCE_OBJECT), false);
            }

            $this->{$property} = $value;
        }
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
