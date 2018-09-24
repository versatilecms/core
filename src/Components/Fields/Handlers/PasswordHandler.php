<?php

namespace Versatile\Core\Components\Fields\Handlers;

use Versatile\Core\Components\Fields\AbstractHandler;

class PasswordHandler extends AbstractHandler
{
    protected $codename = 'password';

    public function createContent($row, $dataType, $dataTypeContent, $options)
    {
        return view('versatile::_components.fields.form.password', [
            'row'             => $row,
            'options'         => $options,
            'dataType'        => $dataType,
            'dataTypeContent' => $dataTypeContent,
        ]);
    }
}
