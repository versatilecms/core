<?php

namespace Versatile\Core\Events;

use Illuminate\Queue\SerializesModels;

class FieldsRegistered
{
    use SerializesModels;

    public $fields;

    public function __construct(array $fields)
    {
        $this->fields = $fields;

        // @deprecate
        //
        event('versatile.form-fields.registered', $fields);
    }
}
