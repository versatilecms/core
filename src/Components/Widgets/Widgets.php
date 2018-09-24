<?php

namespace Versatile\Core\Components\Widgets;

use Arrilot\Widgets\Facade as Widget;

class Widgets
{
    /**
     * @var array
     */
    protected $widgets = [
        UserDimmer::class,
        PostDimmer::class,
        PageDimmer::class
    ];

    /**
     * Get a collection of the dashboard widgets.
     *
     * @return \Arrilot\Widgets\WidgetGroup
     */
    public function all()
    {
        // $widgetClasses = config('versatile.dashboard.widgets');
        $dimmers = Widget::group('versatile::dimmers');

        foreach ($this->widgets as $widget) {

            if (app($widget)->shouldBeDisplayed()) {
                $dimmers->addWidget($widget);
            }
        }

        return $dimmers;
    }
}
