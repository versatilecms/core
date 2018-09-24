<?php

namespace Versatile\Core\Database\Types\Postgresql;

use Versatile\Core\Database\Types\Common\DoubleType;

class DoublePrecisionType extends DoubleType
{
    const NAME = 'double precision';
    const DBTYPE = 'float8';
}
