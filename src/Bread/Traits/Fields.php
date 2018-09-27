<?php

namespace Versatile\Core\Bread\Traits;

use Versatile\Core\Bread\Collection;
use Versatile\Core\Database\Schema\SchemaManager;
use Versatile\Core\Bread\DataRow;

trait Fields
{
	public $dataRows = [];

    /**
     * @param array $field
     * @return $this
     */
    public function addDataRow($field)
    {
        if (is_array($field)) {
            $field['dataType'] = $this;
        }

        if (is_object($field)) {
            $field->dataType = $this;
        }

        $this->dataRows[] = new DataRow($field);

        return $this;
    }

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

    public function browseRows()
    {
        return $this->rows()->where('browse', 1);
    }

    public function readRows()
    {
        return $this->rows()->where('read', 1);
    }

    public function editRows()
    {
        return $this->rows()->where('edit', 1);
    }

    public function addRows()
    {
        return $this->rows()->where('add', 1);
    }

    public function deleteRows()
    {
        return $this->rows()->where('delete', 1);
    }

    public function firstRow()
    {
        return $this->rows()->first();
    }

    public function lastRow()
    {
        return $this->rows()->last();
    }

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
}
