<?php

namespace Versatile\Core\Database\Types\Common;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Versatile\Core\Database\Types\Type;

class JsonType extends Type
{
    const NAME = 'json';

    public function getSQLDeclaration(array $field, AbstractPlatform $platform)
    {
        return 'json';
    }
}
