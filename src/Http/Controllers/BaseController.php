<?php

namespace Versatile\Core\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Versatile\Core\Policies\BasePolicy;

use Versatile\Core\Bread\DataType;
use Versatile\Core\Bread\Traits\BreadRelationship;
use Versatile\Core\Models\DataType as DataTypeModel;
use Versatile\Core\Http\Controllers\Operations\Add;
use Versatile\Core\Http\Controllers\Operations\Browse;
use Versatile\Core\Http\Controllers\Operations\Delete;
use Versatile\Core\Http\Controllers\Operations\Edit;
use Versatile\Core\Http\Controllers\Operations\Order;
use Versatile\Core\Http\Controllers\Operations\Read;


class BaseController extends Controller
{
    use Add;
    use Browse;
    use Delete;
    use Edit;
    use Order;
    use Read;
    use BreadRelationship;

    /**
     * Informs if DataType will be loaded from the database or setup
     *
     * @var bool
     */
    protected $dataTypeFromDatabase = true;

    /**
     * TypeName slug to find the record in the database.
     * If null and $fromDatabase is set to true, the second route parameter will be used to search.
     *
     * @var string|null
     */
    protected $dataTypeSlug = null;

    /**
     * DataType instance
     *
     * @var DataType
     */
    public $bread;


    public function __construct()
    {
        if (!$this->bread) {

            $this->bread = app()->make(DataType::class);

            if ($this->dataTypeFromDatabase === true) {
                $dataType = $this->getDataTypeModelInstance();
                $this->bread->makeDataType($dataType);
            }

            $this->setup();
            $this->registerPolicies();
        }

        $this->bread->defineActionsFormat();

    }

    /**
     * Register the application's policies.
     *
     * @return void
     */
    public function registerPolicies()
    {
        if (!$this->bread->policies) {
            $this->bread->policies[$this->bread->model_name] = BasePolicy::class;
        }

        foreach ($this->bread->policies as $model => $policy) {
            Gate::policy($model, $policy);
        }
    }

    /**
     * Gets the instance of the DataType model
     *
     * @return mixed|DataTypeModel
     * @throws \Exception
     */
    public function getDataTypeModelInstance()
    {
        if (is_null($this->dataTypeSlug)) {
            $route = explode('.', request()->route()->getName());

            if (!isset($route[1])) {
                throw new \Exception("DataType name not found on route");
            }

            $this->dataTypeSlug = $route[1];
        }

        $dataType = DataTypeModel::where('slug', '=', $this->dataTypeSlug)->first();

        if (is_null($dataType)) {
            throw new \Exception("DataType {$this->dataTypeSlug} not found in database");
        }

        return $dataType;
    }

    /**
     * Configuration options for a bread.
     */
    public function setup()
    {
    }
}
