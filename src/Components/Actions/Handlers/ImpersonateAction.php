<?php

namespace Versatile\Core\Components\Actions\Handlers;

use Versatile\Core\Components\Actions\AbstractAction;

class ImpersonateAction extends AbstractAction
{
    public function getTitle()
    {
        return __('versatile::generic.impersonate');
    }

    public function getCodename()
    {
        return 'impersonate';
    }

    public function getIcon()
    {
        return 'versatile-person';
    }

    public function getPolicy()
    {
        return 'impersonate';
    }

    public function getAttributes()
    {
        return [
            'class' => 'btn btn-sm btn-success',
        ];
    }

    public function getDefaultRoute()
    {
        return route('versatile.'.$this->dataType->slug.'.impersonate', $this->data->{$this->data->getKeyName()});
    }
}
