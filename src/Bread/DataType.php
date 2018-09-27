<?php

namespace Versatile\Core\Bread;

use Versatile\Core\Bread\Traits\Actions;
use Versatile\Core\Bread\Traits\Fields;
use Versatile\Core\Bread\Traits\Filters;
use Versatile\Core\Bread\Traits\Search;
use Versatile\Core\Bread\Traits\Views;
use Versatile\Core\Contracts\DataTypeInterface;

class DataType implements DataTypeInterface
{
    use Actions;
    use Fields;
    use Filters;
    use Search;
    use Views;

    public $name;
    public $slug;
    public $display_name_singular;
    public $display_name_plural;
    public $icon;
    public $model_name;
    public $policy_name;
    public $controller;
    public $description;
    public $generate_permissions;
    public $order_column;
    public $order_display_column;

    public $model;
    public $policy;


    /**
     * This function binds the BREAD to its corresponding Model.
     *
     * @param string $model_namespace Full model namespace. Ex: App\Models\User
     * @throws \Exception in case the model does not exist
     */
    public function setModel($model_namespace)
    {
        if (!class_exists($model_namespace)) {
            throw new \Exception('This model does not exist');
        }

        $this->model = app($model_namespace);
        $this->model_name = $model_namespace;
        //$this->query = $this->model->select('*');
        //$this->entry = null;

        return $this;
    }
    /**
     * Get the corresponding Eloquent Model for the CrudController, as defined with the setModel() function.
     *
     * @return string|\Illuminate\Database\Eloquent\Model
     */
    public function getModel()
    {
        return $this->model;
    }

    public function setPolicy($policy_namespace)
    {
        if (!class_exists($policy_namespace)) {
            throw new \Exception('This policy does not exist');
        }

        $this->policy = app($policy_namespace);
        $this->policy_name = $policy_namespace;

        return $this;
    }

    public function getPolicy()
    {
        return $this->policy;
    }

    public function getDataTypeSlug($request = null)
    {
        return $this->slug;
    }

    public function getDataType($dataTypeSlug = null)
    {
        return $this;
    }

    public function __get($name)
    {
        $name = camel_case($name);
        if (method_exists($this, $name)) {
            return $this->{$name}();
        }

        return null;
    }
}
