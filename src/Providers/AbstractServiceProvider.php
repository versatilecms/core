<?php

namespace Versatile\Core\Providers;

use Illuminate\Support\ServiceProvider;
use Versatile\Core\Contracts\BreadServiceProviderInterface;
use Versatile\Core\Traits\BreadServiceProvider;

abstract class AbstractServiceProvider extends ServiceProvider implements BreadServiceProviderInterface
{
    use BreadServiceProvider;

    /**
     * Define Configs
     */
    public function setConfigs()
    {
        return null;
    }

    /**
     * Define Routes
     */
    public function setRoutes()
    {
        return null;
    }

    /**
     * Define Publishers
     */
    public function setPublishers()
    {
        return null;
    }

    /**
     * Define Views
     */
    public function setViews()
    {
        return null;
    }

    /**
     * Load helpers.
     */
    public function setHelpers()
    {
        return null;
    }

    /**
     * Define Migrations
     */
    public function setMigrations()
    {
        return null;
    }

    /**
     * Define Commands/Schedules
     */
    public function setCommands()
    {
        return null;
    }
}