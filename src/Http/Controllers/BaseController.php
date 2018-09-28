<?php

namespace Versatile\Core\Http\Controllers;

use Illuminate\Http\Request;
use Versatile\Core\Bread\DataType;

use Versatile\Core\Models\DataType as DataTypeModel;
use Versatile\Core\Http\Controllers\Operations\Add;
use Versatile\Core\Http\Controllers\Operations\Browse;
use Versatile\Core\Http\Controllers\Operations\Delete;
use Versatile\Core\Http\Controllers\Operations\Edit;
use Versatile\Core\Http\Controllers\Operations\Order;
use Versatile\Core\Http\Controllers\Operations\Read;

use Versatile\Core\Bread\Traits\BreadRelationship;

class BaseController extends Controller
{
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

    use Add;
    use Browse;
    use Delete;
    use Edit;
    use Order;
    use Read;
    use BreadRelationship;


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

    public function __construct()
    {
        if (!$this->bread) {

            $this->bread = app()->make(DataType::class);

            if ($this->dataTypeFromDatabase === true) {
                $dataType = $this->getDataTypeModelInstance();
                $this->bread->setDataType($dataType);
            }

            $this->setup();
        }

        $this->bread->defineActionsFormat();

    }

    /**
     * Configuration options for a Scaffold.
     */
    public function setup()
    {
    }
}
