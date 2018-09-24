<?php

namespace Versatile\Core\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class VersatileEventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'Versatile\Core\Events\BreadAdded' => [
            'Versatile\Core\Listeners\AddBreadMenuItem',
            'Versatile\Core\Listeners\AddBreadPermission',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}
