<?php

namespace Versatile\Core\Database\Types\Common;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Versatile\Core\Database\Types\Type;

class TextType extends Type
{
    const NAME = 'text';

    public function getSQLDeclaration(array $field, AbstractPlatform $platform)
    {
        return 'text';
    }
}
