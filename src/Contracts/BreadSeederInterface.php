<?php

namespace Versatile\Core\Contracts;

interface BreadSeederInterface
{
    public function bread();

    public function inputFields();

    public function menu();

    public function settings();

    public function permissions();

    public function files();

    public function extras();
}
