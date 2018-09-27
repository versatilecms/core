<?php

namespace Versatile\Core\Http\Controllers;

use Illuminate\Http\Request;
use Versatile\Core\Bread\DataType;

use Versatile\Core\Models\DataType as DataTypeModel;
use Versatile\Core\Bread\Operations\Add;
use Versatile\Core\Bread\Operations\Browse;
use Versatile\Core\Bread\Operations\Delete;
use Versatile\Core\Bread\Operations\Edit;
use Versatile\Core\Bread\Operations\Order;
use Versatile\Core\Bread\Operations\Read;

use Versatile\Core\Bread\Traits\BreadRelationship;

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
     * @var DataType
     */
    public $bread;

    public function __construct()
    {
        if (!$this->bread) {

            $slug = explode('.', request()->route()->getName())[1];

            $dataType = DataTypeModel::where('slug', '=', $slug)->first();
            $this->bread = (new DataType)->setDataType($dataType);

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
