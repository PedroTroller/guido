<?php

namespace Guido\Schema\Combination;

use Guido\Schema\Combination;
use stdClass;
use Guido\Bogus\Builder;
use Faker\Factory;
use Faker\Generator;

class AnyOfCombination implements Combination
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

    public function generate(stdClass $schema)
    {
        $subSchema = $this->faker->randomElement($schema->anyOf);

        return $this->builder->build($subSchema)->generate($subSchema);
    }
}
