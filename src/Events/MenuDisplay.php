<?php

namespace Versatile\Core\Events;

use Illuminate\Queue\SerializesModels;
use Versatile\Core\Models\Menu;

class MenuDisplay
{
    use SerializesModels;

    public $menu;

    public function __construct(Menu $menu)
    {
        $this->menu = $menu;

        // @deprecate
        //
        event('versatile.menu.display', $menu);
    }
}
