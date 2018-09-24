<?php

namespace Versatile\Core\Components\Actions\Handlers;

use Versatile\Core\Components\Actions\AbstractAction;

class DeleteAction extends AbstractAction
{
    public function getTitle()
    {
        return __('versatile::generic.delete');
    }

    public function getCodename()
    {
        return 'delete';
    }

    public function getIcon()
    {
        return 'versatile-trash';
    }

    public function getPolicy()
    {
        return 'delete';
    }

    public function getAttributes()
    {
        return [
            'class' => 'btn btn-sm btn-danger delete',
            'data-id' => $this->data->{$this->data->getKeyName()},
            'id' => 'delete-' . $this->data->{$this->data->getKeyName()},
            'data-action' => 'delete'
        ];
    }

    public function getDefaultRoute()
    {
        return 'javascript:;';
    }
}
