<?php

namespace Versatile\Core\Contracts;

interface ModuleInterface
{

    /**
     * Get path.
     *
     * @return string
     */
    public function getPath();

    /**
     * Determine whether the given status same with the current module status.
     *
     * @param $status
     *
     * @return bool
     */
    public function isStatus($status);

    /**
     * Determine whether the current module activated.
     *
     * @return bool
     */
    public function enabled();
    
    /**
     *  Determine whether the current module not disabled.
     *
     * @return bool
     */
    public function disabled();

    /**
     * Set active state for current module.
     *
     * @param $active
     *
     * @return bool
     */
    public function setActive($active);

    /**
     * Disable the current module.
     */
    public function disable();

    /**
     * Enable the current module.
     */
    public function enable();
    
    /**
     * Delete the current module.
     *
     * @return bool
     */
    public function delete();
}
