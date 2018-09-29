<?php

namespace Versatile\Core\Events;

use Illuminate\Queue\SerializesModels;
use Versatile\Core\Contracts\DataTypeInterface;

class BreadDeleted
{
    use SerializesModels;

    public $dataType;

    public $data;

    public function __construct(DataTypeInterface $dataType, $data)
    {
        $this->dataType = $dataType;

        $this->data = $data;

        event(new BreadChanged($dataType, $data, 'Deleted'));
    }
}
