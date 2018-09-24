<?php

namespace Versatile\Core\Contracts;

interface BreadServiceProviderInterface
{
    /**
     * Define Configs
     */
    public function setConfigs();

    /**
     * Define Routes
     */
    public function setRoutes();

    /**
     * Define Publishers
     */
    public function setPublishers();

    /**
     * Define Views
     */
    public function setViews();

    /**
     * Load helpers.
     */
    public function setHelpers();

    /**
     * Define Migrations
     */
    public function setMigrations();

    /**
     * Define Commands/Schedules
     */
    public function setCommands();
}
