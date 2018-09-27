<?php

namespace Versatile\Core\Bread\Traits;

trait Views
{

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
