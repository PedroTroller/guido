<?php

namespace Guido;

use stdClass;

interface Bogus
{
    /**
     * @param stdClass $schema
     *
     * @return mixed
     */
    public function generate(stdClass $schema);
}
