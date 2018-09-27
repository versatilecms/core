<?php

namespace Versatile\Core\Bread\Traits;

trait Views
{
	protected $browseView = null;
	protected $readView = null;
	protected $editView = null;
	protected $addView = null;
	protected $orderView = null;

	public function setBrowseView($view)
    {
        $this->browseView = $view;

        return $this;
    }

	public function setReadView($view)
    {
        $this->readView = $view;

        return $this;
    }

    public function setEditAddView($view)
    {
        return $this->setEditView($view)->setAddView($view);
    }

	public function setEditView($view)
    {
        $this->editView = $view;

        return $this;
    }

	public function setAddView($view)
    {
        $this->addView = $view;

        return $this;
    }

	public function setOrderView($view)
    {
        $this->orderView = $view;

        return $this;
    }

	public function getView($operation)
    {
    	$view = $this->{$operation.'View'};

    	if (is_null($view)) {
    		return config('versatile.bread.views.' . $operation, 'versatile::bread.' . $operation);
    	}

    	return $view;
    }

	public function getBrowseView()
    {
        return $this->getView('browse');
    }

	public function getReadView()
    {
        return $this->getView('read');
    }

	public function getEditView()
    {
        return $this->getView('edit');
    }

	public function getAddView()
    {
        return $this->getView('add');
    }

	public function getOrderView()
    {
        return $this->getView('order');
    }

	public function setDisplayName($singular, $plural)
    {
        $this->setDisplayNameSingular($singular);
        $this->setDisplayNamePlural($plural);

        return $this;
    }

	public function setDisplayNameSingular($nameSingular)
    {
        $this->display_name_singular = $nameSingular;

        return $this;
    }

	public function setDisplayNamePlural($namePlural)
    {
        $this->display_name_plural = $namePlural;

        return $this;
    }

	public function setIcon($icon)
    {
        $this->icon = $icon;

        return $this;
    }
}
