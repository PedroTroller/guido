<?php

namespace Guido\Bogus\Builder;

class Context
{
    const OBJECT_REQUIRED_PROPERTIES = 0;
    const OBJECT_RANDOM_PROPERTIES = 1;
    const OBJECT_ALL_PROPERTIES = 2;

    /**
     * @var int
     */
    private $generationPolicy = Context::OBJECT_ALL_PROPERTIES;

    public function getGenerationPolicy(): int
    {
        return $this->generationPolicy;
    }

    public function setGenerationPolicy(int $generationPolicy)
    {
        $this->generationPolicy = $generationPolicy;
    }
}
