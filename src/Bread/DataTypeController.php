<?php

namespace Versatile\Core\Bread;

use Versatile\Core\Http\Controllers\Controller;

use Versatile\Core\Bread\Operations\Add;
use Versatile\Core\Bread\Operations\Browse;
use Versatile\Core\Bread\Operations\Delete;
use Versatile\Core\Bread\Operations\Edit;
use Versatile\Core\Bread\Operations\Order;
use Versatile\Core\Bread\Operations\Read;

use Versatile\Core\Bread\Traits\BreadRelationship;

class DataTypeController extends Controller
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
            $this->bread = app()->make(DataType::class);
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
