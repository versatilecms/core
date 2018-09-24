<?php

namespace Versatile\Core\Database\Types\Mysql;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Versatile\Core\Database\Types\Type;

class TimeStampType extends Type
{
    const NAME = 'timestamp';

    public function getSQLDeclaration(array $field, AbstractPlatform $platform)
    {
        if (isset($field['default'])) {
            return 'timestamp';
        }

        return 'timestamp null';
    }
}
