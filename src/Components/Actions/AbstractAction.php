<?php

namespace Versatile\Core\Components\Actions;

abstract class AbstractAction implements ActionInterface
{
    protected $dataType;
    protected $data;

    public function __construct($dataType, $data)
    {
        $this->dataType = $dataType;
        $this->data = $data;
    }

    /**
     * Get the class name and convert to snake_case
     * @return string
     */
    public function getName()
    {
        $name = class_basename($this);
        if (ends_with($name, 'Action')) {
            $name = substr($name, 0, -strlen('Action'));
        }

        return snake_case($name);
    }

    public function getDataType()
    {
    }

    public function getPolicy()
    {
    }

    public function getRoute($key)
    {
        if (method_exists($this, $method = 'get' . ucfirst($key) . 'Route')) {
            return $this->$method();
        } else {
            return $this->getDefaultRoute();
        }
    }

    public function getAttributes()
    {
        return [];
    }

    public function convertAttributesToHtml($only = [], $exclude = [])
    {
        $attributes = $this->getAttributes();

        foreach ($attributes as $key => $attribute) {
            if (!empty($only) && is_array($only) && !in_array($key, $only)) {
                unset($attributes[$key]);
            }

            if (!empty($exclude) && is_array($exclude) && in_array($key, $exclude)) {
                unset($attributes[$key]);
            }
        }

        $result = '';

        foreach ($attributes as $key => $attribute) {
            $result .= $key . '="' . $attribute . '"';
        }

        return $result;
    }

    public function shouldActionDisplayOnDataType()
    {
        return $this->dataType->name === $this->getDataType() || $this->getDataType() === null;
    }
}
