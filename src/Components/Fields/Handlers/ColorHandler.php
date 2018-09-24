<?php

namespace Versatile\Core\Components\Fields\Handlers;

use Versatile\Core\Components\Fields\AbstractHandler;

class ColorHandler extends AbstractHandler
{
    protected $codename = 'color';

    public function createContent($row, $dataType, $dataTypeContent, $options)
    {
        return view('versatile::_components.fields.form.color', [
            'row'             => $row,
            'options'         => $options,
            'dataType'        => $dataType,
            'dataTypeContent' => $dataTypeContent,
        ]);
    }
}
