<?php

namespace Versatile\Core\Components\Fields\Handlers;

use Versatile\Core\Components\Fields\AbstractHandler;

class ImageHandler extends AbstractHandler
{
    protected $codename = 'image';

    public function createContent($row, $dataType, $dataTypeContent, $options)
    {
        return view('versatile::_components.fields.form.image', [
            'row'             => $row,
            'options'         => $options,
            'dataType'        => $dataType,
            'dataTypeContent' => $dataTypeContent,
        ]);
    }
}
