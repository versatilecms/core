<?php

namespace Versatile\Core\Components\Actions\Handlers;

use Versatile\Core\Components\Actions\AbstractAction;

class ViewAction extends AbstractAction
{
    public function getTitle()
    {
        return __('versatile::generic.view');
    }

    public function getCodename()
    {
        return 'view';
    }

    public function getIcon()
    {
        return 'versatile-eye';
    }

    public function getPolicy()
    {
        return 'read';
    }

    public function getAttributes()
    {
        return [
            'class' => 'btn btn-sm btn-warning view',
        ];
    }

    public function getDefaultRoute()
    {
        return route('versatile.'.$this->dataType->slug.'.show', $this->data->{$this->data->getKeyName()});
    }
}
