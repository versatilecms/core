<?php

namespace Versatile\Core\Database\Types\Postgresql;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Versatile\Core\Database\Types\Type;

class TsVectorType extends Type
{
    const NAME = 'tsvector';

    public function getSQLDeclaration(array $field, AbstractPlatform $platform)
    {
        return 'tsvector';
    }
}
