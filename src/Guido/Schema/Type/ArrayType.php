<?php

namespace Guido\Schema\Type;

use stdClass;
use Guido\Schema\Type;
use Guido\Bogus\Builder;
use Faker\Generator;
use Faker\Factory;

class ArrayType implements Type
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

    public function generate(stdClass $schema): array
    {
        $minimum = isset($schema->minItems)
            ? $schema->minItems
            : 0
        ;

        $maximum = isset($schema->maxItems)
            ? $schema->maxItems
            : ($minimum ?: 1) * 10
        ;

        $number = $this->faker->numberBetween($minimum, $maximum);

        $result = [];

        for ($index = 0; $index < $number; $index++) {
            $result[$index] = $this->generateSubSchema($index, isset($schema->items) ? $schema->items : null);
        }

        return $result;
    }

    private function generateSubSchema(int $index, $schema = null)
    {
        if ($schema instanceof stdClass) {
            return $this->builder->build($schema)->generate($schema);
        }

        if (is_array($schema)) {
            if (array_key_exists($index, $schema)) {
                return $this->generateSubSchema($index, $schema[$index]);
            }

            return $this->generateSubSchema($index);
        }

        return $this->faker->boolean
            ? $this->faker->word
            : $this->faker->numberBetween
        ;
    }
}
