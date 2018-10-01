<?php

namespace Versatile\Core\Bread\Processors;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

use Versatile\Core\Bread\DataType;

class DataTypeProcessor
{
    /**
     * @var array
     */
    public $fieldsDefinitions = [];

    /**
     * @var Model|LengthAwarePaginator|Collection
     */
    public $dataTypeContent;

    /**
     * Processed data output.
     *
     * @var LengthAwarePaginator|Collection|Model
     */
    protected $output;

    /**
     * DataTypeProcessor constructor.
     *
     * @param DataType $dataType
     * @param LengthAwarePaginator|Collection|Model $dataTypeContent
     * @throws \Exception
     */
    public function __construct(DataType $dataType, $dataTypeContent)
    {
        $this->dataTypeContent = $dataTypeContent;
        $this->fieldsDefinitions = $dataType->fieldsDefinitions;

        switch (true) {
            case $dataTypeContent instanceof LengthAwarePaginator:
            case $dataTypeContent instanceof Collection:
                $this->output = $this->processList();
                break;

            case $dataTypeContent instanceof Model:
                $this->output = $this->process();
                break;

            default:
                throw new \Exception('Relation ' . get_class($dataTypeContent) . ' is not yet supported.');
        }
    }

    /**
     * @return LengthAwarePaginator|Collection
     */
    public function processList()
    {
        $this->dataTypeContent->map(function ($row) {

            foreach ($this->fieldsDefinitions as $field => $callback) {
                $row->{$field} = $callback($row);
            }

            return $row;
        });

        return $this->dataTypeContent;
    }

    /**
     * @return Model
     */
    public function process()
    {
        foreach ($this->fieldsDefinitions as $field => $callback) {
            $this->dataTypeContent->{$field} = $callback($this->dataTypeContent);
        }

        return $this->dataTypeContent;
    }

    /**
     * @return array|Model|LengthAwarePaginator|Collection
     */
    public function output()
    {
        return $this->output;
    }

}
