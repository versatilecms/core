<?php

namespace Versatile\Core\Events;

use Illuminate\Queue\SerializesModels;
use Versatile\Core\Contracts\DataTypeInterface as DataType;

class BreadDataChanged
{
    use SerializesModels;

    public $dataType;

    public $data;

    public $changeType;

    public function __construct(DataType $dataType, $data, $changeType)
    {
        $this->dataType = $dataType;

        $this->data = $data;

        $this->changeType = $changeType;
    }
}
