<?php

namespace Guido\Schema\Reference;

use Guido\Schema\Reference;
use stdClass;
use Guido\Bogus\Builder;

class Local implements Reference
{
    /**
     * @var Builder
     */
    private $builder;

    /**
     * @var stdClass
     */
    private $schema;

    public function __construct(Builder $builder, stdClass $schema)
    {
        $this->builder = $builder;
        $this->schema = $schema;
    }

    public function generate(stdClass $schema)
    {
        $refKey = '$ref';
        $ref = $schema->$refKey;
        $path = ltrim($ref, '#/');
        $parts = explode('/', $path);

        $subSchema = $this->schema;

        foreach ($parts as $key) {
            $subSchema = $subSchema->$key;
        }

        return $this->builder->build($subSchema)->generate($subSchema);
    }
}
