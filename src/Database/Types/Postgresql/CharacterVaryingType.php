<?php

namespace Versatile\Core\Database\Types\Postgresql;

use Versatile\Core\Database\Types\Common\VarCharType;

class CharacterVaryingType extends VarCharType
{
    const NAME = 'character varying';
    const DBTYPE = 'varchar';
}
