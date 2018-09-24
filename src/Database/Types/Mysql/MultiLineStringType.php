<?php

namespace Versatile\Core\Database\Types\Mysql;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Versatile\Core\Database\Types\Type;

class MultiLineStringType extends Type
{
    const NAME = 'multilinestring';

    public function getSQLDeclaration(array $field, AbstractPlatform $platform)
    {
        return 'multilinestring';
    }
}
