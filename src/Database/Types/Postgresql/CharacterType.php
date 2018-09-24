<?php

namespace Versatile\Core\Database\Types\Postgresql;

use Versatile\Core\Database\Types\Common\CharType;

class CharacterType extends CharType
{
    const NAME = 'character';
    const DBTYPE = 'bpchar';
}
