<?php

namespace Versatile\Core\Bread;

use Versatile\Core\Models\DataType as DataTypeModel;

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

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $slug;

    /**
     * @var string
     */
    public $display_name_singular;

    /**
     * @var string
     */
    public $display_name_plural;

    /**
     * @var string
     */
    public $icon;

    /**
     * @var string
     */
    public $model_name;
    /**
     * @var string
     */
    public $policy_name;

    /**
     * @deprecated
     * @var string
     */
    public $controller;

    /**
     * @var string
     */
    public $description;

    /**
     * @var string
     */
    public $generate_permissions;

    /**
     * @var string
     */
    public $order_column = 'order';

    /**
     * @var string
     */
    public $order_display_column;

    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    public $policies = [];

    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    public $model;

    public function setDataType(DataTypeModel $dataType)
    {
        $this->setName($dataType->name);
        $this->setSlug($dataType->slug);
        $this->setDisplayName($dataType->display_name_singular, $dataType->display_name_plural);
        $this->setIcon($dataType->icon);
        $this->setModel($dataType->model_name);

        if (isset($dataType->policy_name) && $dataType->policy_name) {
            $this->addPolicy($dataType->model_name, $dataType->policy_name);
        }

        $this->addDataRows($dataType->rows()->get()->toArray());

        return $this;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }


    /**
     * This function binds the BREAD to its corresponding Model.
     *
     * @param string $model_namespace Full model namespace. Ex: App\Models\User
     * @throws \Exception in case the model does not exist
     * @return $this
     */
    public function setModel($model_namespace)
    {
        if (!class_exists($model_namespace)) {
            throw new \Exception('This model does not exist');
        }

        $this->model = app($model_namespace);
        $this->model_name = $model_namespace;

        return $this;
    }
    /**
     * Get the corresponding Eloquent Model for the CrudController, as defined with the setModel() function.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Add the application's policies.
     *
     * @param string $model
     * @param string $policy
     * @return $this
     * @throws \Exception
     */
    public function addPolicy($model, $policy)
    {
        if (!class_exists($model)) {
            throw new \Exception('This model does not exist');
        }

        if (!class_exists($policy)) {
            throw new \Exception('This policy does not exist');
        }

        $this->policies[$model] = $policy;

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
