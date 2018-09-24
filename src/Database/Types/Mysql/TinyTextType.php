<?php

namespace Versatile\Core\Database\Types\Mysql;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Versatile\Core\Database\Types\Type;

class TinyTextType extends Type
{
    const NAME = 'tinytext';

    public function getSQLDeclaration(array $field, AbstractPlatform $platform)
    {
        return 'tinytext';
    }
}
