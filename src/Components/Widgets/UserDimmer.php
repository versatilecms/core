<?php

namespace Versatile\Core\Components\Widgets;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Versatile\Core\Facades\Versatile;

class UserDimmer extends BaseDimmer
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [];

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        $count = Versatile::model('User')->count();
        $string = trans_choice('versatile::dimmer.user', $count);

        return view('versatile::_components.widgets.dimmer', array_merge($this->config, [
            'icon'   => 'versatile-group',
            'title'  => "{$count} {$string}",
            'text'   => __('versatile::dimmer.user_text', ['count' => $count, 'string' => Str::lower($string)]),
            'button' => [
                'text' => __('versatile::dimmer.user_link_text'),
                'link' => route('versatile.users.index'),
            ],
            'image' => versatile_asset('images/widget-backgrounds/01.jpg'),
        ]));
    }

    /**
     * Determine if the widget should be displayed.
     *
     * @return bool
     */
    public function shouldBeDisplayed()
    {
        return Auth::user()->can('browse', Versatile::model('User'));
    }
}
