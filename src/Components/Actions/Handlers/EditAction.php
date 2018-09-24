<?php

namespace Versatile\Core\Components\Actions\Handlers;

use Versatile\Core\Components\Actions\AbstractAction;

class EditAction extends AbstractAction
{
    public function getTitle()
    {
        return __('versatile::generic.edit');
    }

    public function getCodename()
    {
        return 'edit';
    }

    public function getIcon()
    {
        return 'versatile-edit';
    }

    public function getPolicy()
    {
        return 'edit';
    }

    public function getAttributes()
    {
        return [
            'class' => 'btn btn-sm btn-primary edit',
        ];
    }

    public function getDefaultRoute()
    {
        return route('versatile.'.$this->dataType->slug.'.edit', $this->data->{$this->data->getKeyName()});
    }
}
