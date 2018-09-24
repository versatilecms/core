<?php

namespace Versatile\Core\Components\Widgets;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Versatile\Posts\Post;

class PostDimmer extends BaseDimmer
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
        $count = Post::count();
        $string = trans_choice('versatile::dimmer.post', $count);

        return view('versatile::_components.widgets.dimmer', array_merge($this->config, [
            'icon'   => 'versatile-news',
            'title'  => "{$count} {$string}",
            'text'   => __('versatile::dimmer.post_text', ['count' => $count, 'string' => Str::lower($string)]),
            'button' => [
                'text' => __('versatile::dimmer.post_link_text'),
                'link' => route('versatile.posts.index'),
            ],
            'image' => versatile_asset('images/widget-backgrounds/02.jpg'),
        ]));
    }

    /**
     * Determine if the widget should be displayed.
     *
     * @return bool
     */
    public function shouldBeDisplayed()
    {
        return Auth::user()->can('browse', app(Post::class));
    }
}
