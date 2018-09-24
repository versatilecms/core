<?php

namespace Versatile\Core\Listeners;

use Versatile\Core\Events\BreadAdded;
use Versatile\Core\Facades\Versatile;
use Versatile\Core\Models\Menu;
use Versatile\Core\Models\MenuItem;

class AddBreadMenuItem
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Create a MenuItem for a given BREAD.
     *
     * @param BreadAdded $event
     *
     * @return void
     */
    public function handle(BreadAdded $bread)
    {
        if (config('versatile.bread.add_menu_item') && file_exists(base_path('routes/web.php'))) {
            require base_path('routes/web.php');

            $menu = Menu::where('name', config('versatile.bread.default_menu'))->firstOrFail();

            $menuItem = MenuItem::firstOrNew([
                'menu_id' => $menu->id,
                'title'   => $bread->dataType->display_name_plural,
                'url'     => '',
                'route'   => 'versatile.'.$bread->dataType->slug.'.index',
            ]);

            $order = Versatile::model('MenuItem')->highestOrderMenuItem();

            if (!$menuItem->exists) {
                $menuItem->fill([
                    'target'     => '_self',
                    'icon_class' => $bread->dataType->icon,
                    'color'      => null,
                    'parent_id'  => null,
                    'order'      => $order,
                ])->save();
            }
        }
    }
}
