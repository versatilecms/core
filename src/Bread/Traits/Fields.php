<?php

namespace Versatile\Core\Bread\Traits;

use Versatile\Core\Bread\Collection;
use Versatile\Core\Database\Schema\SchemaManager;
use Versatile\Core\Bread\DataRow;
use Versatile\Core\Bread\Processors\DataTypeProcessor;

trait Fields
{
    /**
     * @var array
     */
	public $dataRows = [];

    /**
     * Fields definitions container (browse, read, edit).
     *
     * @var array
     */
    public $fieldsDefinitions = [];

    /**
     * Data rows definitions container (browse, read, edit, add).
     *
     * @var array
     */
    public $dataRowsDefinitions = [];

    /**
     * @param $field
     * @param $callback
     * @return $this
     * @throws \Exception
     */
    public function editDataRowContent($field, $callback)
    {
        if(!isset($this->dataRows[$field])) {
            throw new \Exception("The {$field} field does not exist in the list of rows");
        }

        $this->fieldsDefinitions[$field] = $callback;

        return $this;
    }

    /**
     * @param string $field
     * @param $callback
     * @return $this
     * @throws \Exception
     */
    public function editDataRow($field, $callback)
    {
        if(!isset($this->dataRows[$field])) {
            throw new \Exception("The {$field} data row does not exist in the list of rows");
        }

        $this->dataRows[$field] = $callback($this->dataRows[$field]);

        return $this;
    }

    /**
     * @param $dataTypeContent
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Pagination\LengthAwarePaginator|\Illuminate\Support\Collection
     * @throws \Exception
     */
    public function process($dataTypeContent)
    {
        $processor = new DataTypeProcessor($this, $dataTypeContent);
        return $processor->output();
    }

    /**
     * @param array|object $field
     * @return $this
     */
    public function addDataRow($field)
    {
        if (is_array($field)) {
            $field['dataType'] = $this;
            $key = $field['field'];

        }else {
            $field->dataType = $this;
            $key = $field->field;
        }

        $this->dataRows[$key] = new DataRow($field);

        return $this;
    }

    /**
     * @param array $fields
     * @return $this
     */
    public function addDataRows($fields)
    {
        foreach($fields as $field) {
            $this->addDataRow($field);
        }

        return $this;
    }

    /**
     * Get all data rows as collection instance.
     *
     * @return Collection
     */
    public function rows()
    {
        return new Collection($this->dataRows);
    }

    /**
     * @return Collection
     */
    public function browseRows()
    {
        return $this->rows()->where('browse', true);
    }

    /**
     * @return Collection
     */
    public function readRows()
    {
        return $this->rows()->where('read', true);
    }

    /**
     * @return Collection
     */
    public function editRows()
    {
        return $this->rows()->where('edit', true);
    }

    /**
     * @return Collection
     */
    public function addRows()
    {
        return $this->rows()->where('add', true);
    }

    /**
     * @return Collection
     */
    public function deleteRows()
    {
        return $this->rows()->where('delete', true);
    }

    /**
     * Get the first item from the collection rows.
     *
     * @return mixed
     */
    public function firstRow()
    {
        return $this->rows()->first();
    }

    /**
     * Get the last item from the collection rows.
     *
     * @return mixed
     */
    public function lastRow()
    {
        return $this->rows()->last();
    }

    /**
     * Returns all columns in the table
     *
     * @param null|string $name BREAD name, same table name
     * @return array
     */
    public function fields($name = null)
    {
        if (is_null($name)) {
            $name = $this->name;
        }

        $fields = SchemaManager::listTableColumnNames($name);

        return $fields;
    }

    public function fieldOptions()
    {
        $table = $this->name;

        // Get ordered BREAD fields
        $orderedFields = $this->rows()->pluck('field')->toArray();

        $_fieldOptions = SchemaManager::describeTable($table)->toArray();

        $fieldOptions = [];
        $f_size = count($orderedFields);
        for ($i = 0; $i < $f_size; $i++) {
            $fieldOptions[$orderedFields[$i]] = $_fieldOptions[$orderedFields[$i]];
        }
        $fieldOptions = collect($fieldOptions);

        return $fieldOptions;
    }

    public function getSortedFields()
    {
        $fields = [];
        foreach (request()->sorts() as $field) {

            $column = ltrim($field, '-');
            $direction = ($field[0] === '-') ? 'desc' : 'asc';

            $fields[$column] = $direction;
        }

        return $fields;
    }
}
