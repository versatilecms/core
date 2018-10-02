<?php

namespace Versatile\Core\Bread\Traits;

use Illuminate\Support\Facades\View;

trait Views
{
    /**
     * @var null|string
     */
	protected $browseView = null;

    /**
     * @var null|string
     */
	protected $readView = null;

    /**
     * @var null|string
     */
	protected $editView = null;

    /**
     * @var null|string
     */
	protected $addView = null;

    /**
     * @var null|string
     */
	protected $orderView = null;

    /**
     * Set browse template view
     *
     * @param string $view
     * @return $this
     */
	public function setBrowseView($view)
    {
        $this->browseView = $view;

        return $this;
    }

    /**
     * Set read template view
     *
     * @param string $view
     * @return $this
     */
	public function setReadView($view)
    {
        $this->readView = $view;

        return $this;
    }

    /**
     * Set edit-add template view
     *
     * @param string $view
     * @return $this
     */
    public function setEditAddView($view)
    {
        return $this->setEditView($view)->setAddView($view);
    }

    /**
     * Set edit template view
     *
     * @param string $view
     * @return $this
     */
	public function setEditView($view)
    {
        $this->editView = $view;

        return $this;
    }

    /**
     * Set add template view
     *
     * @param string $view
     * @return $this
     */
	public function setAddView($view)
    {
        $this->addView = $view;

        return $this;
    }

    /**
     * Set order template view
     *
     * @param string $view
     * @return $this
     */
	public function setOrderView($view)
    {
        $this->orderView = $view;

        return $this;
    }

    /**
     * Get operation template view
     *
     * @param string $operation browse, read, edit, add or order
     * @return string
     */
    public function getView($operation)
    {
        // View defined in controller setup
        $view = $this->{$operation.'View'};
        if (!is_null($view)) {
            return $view;
        }

        // You can override any of the BREAD views by creating a new folder in resources/views/vendor/versatile/slug-name
        // and slug-name will be the slug that you have assigned for that bread instance.
        if (View::exists("versatile::{$this->slug}.$operation")) {
            return "versatile::{$this->slug}.$operation";
        }

        // Get the default view defined in the configuration file
        $view = config('versatile.bread.views.' . $operation, 'versatile::bread.' . $operation);

        return $view;
    }

    /**
     * Get browse template view
     *
     * @return string
     */
	public function getBrowseView()
    {
        return $this->getView('browse');
    }

    /**
     * Get read template view
     *
     * @return string
     */
	public function getReadView()
    {
        return $this->getView('read');
    }

    /**
     * Get edit template view
     *
     * @return string
     */
	public function getEditView()
    {
        return $this->getView('edit');
    }

    /**
     * Get add template view
     *
     * @return string
     */
	public function getAddView()
    {
        return $this->getView('add');
    }

    /**
     * Get order template view
     *
     * @return string
     */
	public function getOrderView()
    {
        return $this->getView('order');
    }


    /**
     * Set the bread name in singular and plural.
     * Used all over the BREAD interface.
     *
     * @param string $singular
     * @param string $plural
     * @return $this
     */
	public function setDisplayName($singular, $plural)
    {
        $this->setDisplayNameSingular($singular);
        $this->setDisplayNamePlural($plural);

        return $this;
    }

    /**
     * Set bread name in singular.
     * Used all over the BREAD interface.
     *
     * @param string $nameSingular
     * @return $this
     */
	public function setDisplayNameSingular($nameSingular)
    {
        $this->display_name_singular = $nameSingular;

        return $this;
    }

    /**
     * Set bread name in plural.
     * Used all over the BREAD interface.
     *
     * @param string $namePlural
     * @return $this
     */
	public function setDisplayNamePlural($namePlural)
    {
        $this->display_name_plural = $namePlural;

        return $this;
    }

    /**
     * Set the bread icon
     *
     * @param string $icon
     * @return $this
     */
	public function setIcon($icon)
    {
        $this->icon = $icon;

        return $this;
    }
}
