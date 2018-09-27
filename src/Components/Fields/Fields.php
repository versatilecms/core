<?php

namespace Versatile\Core\Components\Fields;

use Versatile\Core\Components\Fields\After\HandlerInterface as AfterHandlerInterface;

class Fields
{
    protected $fields = [];
    protected $afterFields = [];


    public function formField($row, $dataType, $dataTypeContent)
    {
        $formField = $this->fields[$row->type];

        return $formField->handle($row, $dataType, $dataTypeContent);
    }

    public function afterFields($row, $dataType, $dataTypeContent)
    {
        $options = $row->details;

        return collect($this->afterFields)->filter(function ($after) use ($row, $dataType, $dataTypeContent, $options) {
            return $after->visible($row, $dataType, $dataTypeContent, $options);
        });
    }

    /**
     * @param $handler
     * @return $this
     */
    public function addFormField($handler)
    {
        if (!$handler instanceof HandlerInterface) {
            $handler = app($handler);
        }

        $this->fields[$handler->getCodename()] = $handler;

        return $this;
    }

    /**
     * @param $handler
     * @return $this
     */
    public function addAfterFormField($handler)
    {
        if (!$handler instanceof AfterHandlerInterface) {
            $handler = app($handler);
        }

        $this->afterFields[$handler->getCodename()] = $handler;

        return $this;
    }

    /**
     * @return static
     */
    public function fields()
    {
        $connection = config('database.default');
        $driver = config("database.connections.{$connection}.driver", 'mysql');

        return collect($this->fields)->filter(function ($after) use ($driver) {
            return $after->supports($driver);
        });
    }
}
