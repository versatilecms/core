<?php

namespace Versatile\Core\Components\Fields\Handlers;

use Versatile\Core\Components\Fields\AbstractHandler;

class MarkdownEditorHandler extends AbstractHandler
{
    protected $codename = 'markdown_editor';

    public function createContent($row, $dataType, $dataTypeContent, $options)
    {
        return view('versatile::_components.fields.form.markdown_editor', [
            'row'             => $row,
            'options'         => $options,
            'dataType'        => $dataType,
            'dataTypeContent' => $dataTypeContent,
        ]);
    }
}
