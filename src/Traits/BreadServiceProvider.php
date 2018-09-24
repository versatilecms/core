<?php

namespace Versatile\Core\Traits;

trait BreadServiceProvider
{
    /**
     * Bootstrap the application services
     *
     * @return void
     */
    public function boot()
    {
        $this->strapRoutes();
        $this->strapPublishers();
        $this->strapViews();
        $this->strapHelpers();
        $this->strapMigrations();
        $this->strapCommands();
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->strapConfigs();
    }

    /**
     * Bootstrap our Configs
     */
    protected function strapConfigs()
    {
    }

    /**
     * Bootstrap our Routes
     */
    protected function strapRoutes()
    {
    }

    /**
     * Bootstrap our Publishers
     */
    protected function strapPublishers()
    {
    }

    /**
     * Bootstrap our Views
     */
    protected function strapViews()
    {
    }

    /**
     * Load helpers.
     */
    protected function strapHelpers()
    {
    }

    /**
     * Bootstrap our Migrations
     */
    protected function strapMigrations()
    {
    }

    /**
     * Bootstrap our Commands/Schedules
     */
    protected function strapCommands()
    {
    }
}
