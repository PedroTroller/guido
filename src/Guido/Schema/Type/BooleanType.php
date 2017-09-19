<?php

namespace Guido\Schema\Type;

use stdClass;
use Guido\Schema\Type;
use Faker\Generator;
use Faker\Factory;

class BooleanType implements Type
{
    /**
     * @var Generator
     */
    private $faker;

    public function __construct(Generator $faker = null)
    {
        $this->faker = $faker ?: Factory::create();
    }

    public function generate(stdClass $stdClass): bool
    {
        return $this->faker->boolean;
    }
}
