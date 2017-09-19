<?php

namespace Guido\Schema\Type;

use Guido\Schema\Type;
use stdClass;
use Guido\Bogus\Builder;
use Faker\Factory;
use Faker\Generator;
use Guido\Bogus\Builder\Context;

class ObjectType implements Type
{
    /**
     * @var Builder
     */
    private $builder;

    /**
     * @var Generator
     */
    private $faker;

    public function __construct(Builder $builder, Generator $faker = null)
    {
        $this->builder = $builder;
        $this->faker = $faker ?: Factory::create();
    }

    public function generate(stdClass $schema): stdClass
    {
        $object = new stdClass;

        switch ($this->builder->getContext()->getGenerationPolicy()) {
            case Context::OBJECT_REQUIRED_PROPERTIES:
                if (isset($schema->required)) {
                    foreach ($schema->required as $key) {
                        $subSchema = $schema->properties->$key;
                        $object->$key = $this->builder->build($subSchema)->generate($subSchema);
                    }
                }
                break;
            case Context::OBJECT_ALL_PROPERTIES:
                if (isset($schema->properties)) {
                    foreach ($schema->properties as $key => $subSchema) {
                        $object->$key = $this->builder->build($subSchema)->generate($subSchema);
                    }
                }
                break;
        }

        return $object;
    }
}
