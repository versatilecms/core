<?php

namespace Versatile\Core\Bread;

class DataRow
{
    /**
     * @var string
     */
    public $field;

    /**
     * @var string
     */
    public $type;

    /**
     * @var string
     */
    public $display_name;

    /**
     * @var boolean
     */
    public $required = false;

    /**
     * @var boolean
     */
    public $browse = true;

    /**
     * @var boolean
     */
    public $read = true;

    /**
     * @var boolean
     */
    public $edit = true;

    /**
     * @var boolean
     */
    public $add = true;

    /**
     * @var boolean
     */
    public $delete = true;

    /**
     * @var array
     */
    public $details = [];

    /**
     * @var integer
     */
    public $order;


    /**
     * @var DataType
     */
    public $dataType;

    public function __construct($dataRow)
    {
        foreach ($dataRow as $property => $value) {

            if (!property_exists($this, $property)) {
                continue;
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
     * Build the URL to sort data type by this field.
     *
     * @return string Built URL
     */
    public function sortByUrl()
    {
        $params = $_GET;

        $direction = '-'; // default desc

        // If the current direction is desc inverts to asc
        if ($this->isCurrentSortField() && $this->isCurrentSortType() == 'desc') {
            $direction = '';
        }

        $params['sort'] = $direction.$this->field;

        return url()->current().'?'.http_build_query($params);
    }

    /**
     * Check if this field is the current filter.
     *
     * @return bool True if this is the current filter, false otherwise
     */
    public function isCurrentSortField()
    {
        $sortedFields = $this->dataType->getSortedFields();
        return isset($sortedFields[$this->field]);
    }

    /**
     * @return null|string
     */
    public function isCurrentSortType()
    {
        $sortedFields = $this->dataType->getSortedFields();
        return isset($sortedFields[$this->field]) ? $sortedFields[$this->field] : null;
    }
}
