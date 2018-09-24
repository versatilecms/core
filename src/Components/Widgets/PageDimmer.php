<?php

namespace Versatile\Core\Components\Widgets;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Versatile\Pages\Page;

class PageDimmer extends BaseDimmer
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
        $count = Page::count();
        $string = trans_choice('versatile::dimmer.page', $count);

        return view('versatile::_components.widgets.dimmer', array_merge($this->config, [
            'icon'   => 'versatile-file-text',
            'title'  => "{$count} {$string}",
            'text'   => __('versatile::dimmer.page_text', ['count' => $count, 'string' => Str::lower($string)]),
            'button' => [
                'text' => __('versatile::dimmer.page_link_text'),
                'link' => route('versatile.pages.index'),
            ],
            'image' => versatile_asset('images/widget-backgrounds/03.jpg'),
        ]));
    }

    /**
     * Determine if the widget should be displayed.
     *
     * @return bool
     */
    public function shouldBeDisplayed()
    {
        return Auth::user()->can('browse', app(Page::class));
    }
}
