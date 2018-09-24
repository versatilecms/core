<?php

namespace Versatile\Core\Components\Fields\Handlers;

use Versatile\Core\Components\Fields\AbstractHandler;

class SelectMultipleHandler extends AbstractHandler
{
    protected $codename = 'select_multiple';

    public function createContent($row, $dataType, $dataTypeContent, $options)
    {
        return view('versatile::_components.fields.form.select_multiple', [
            'row'             => $row,
            'options'         => $options,
            'dataType'        => $dataType,
            'dataTypeContent' => $dataTypeContent,
        ]);
    }
}
