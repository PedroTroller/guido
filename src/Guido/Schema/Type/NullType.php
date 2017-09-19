<?php

namespace Guido\Schema\Type;

use stdClass;
use Guido\Schema\Type;

class NullType implements Type
{
    public function generate(stdClass $stdClass)
    {
        return null;
    }
}
