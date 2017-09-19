<?php

namespace Guido\Schema\Type;

use stdClass;

class IntegerType extends NumberType
{
    /**
     * {@inheritdoc}
     */
    public function generate(stdClass $schema): int
    {
        return intval(parent::generate($schema));
    }
}
