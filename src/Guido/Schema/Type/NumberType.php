<?php

namespace Guido\Schema\Type;

use stdClass;
use Faker\Generator;
use Faker\Factory;
use Guido\Schema\Type;

class NumberType implements Type
{
    /**
     * @var Generator
     */
    private $faker;

    public function __construct(Generator $faker = null)
    {
        $this->faker = $faker ?: Factory::create();
    }

    /**
     * {@inheritdoc}
     */
    public function generate(stdClass $schema): float
    {
        $minimum = isset($schema->minimum)
            ? $schema->minimum
            : 0
        ;

        $maximum = isset($schema->maximum)
            ? $schema->maximum
            : ($minimum ?: 1) * 100
        ;

        $multipleOf = isset($schema->multipleOf)
            ? $schema->multipleOf
            : 0.01
        ;

        $minimum = intval($minimum * 100);
        $maximum = intval($maximum * 100);
        $multipleOf = intval($multipleOf * 100);

        $exclusiveMinimum = isset($schema->exclusiveMinimum)
            ? $schema->exclusiveMinimum
            : false
        ;

        $exclusiveMaximum = isset($schema->exclusiveMaximum)
            ? $schema->exclusiveMaximum
            : false
        ;

        $numbers = $this->buildSuite($minimum, $maximum, $multipleOf);

        if (true === $exclusiveMinimum) {
            $numbers = array_filter($numbers, function (int $number) use ($minimum) { return $number > $minimum; });
        }

        if (true === $exclusiveMaximum) {
            $numbers = array_filter($numbers, function (int $number) use ($maximum) { return $number < $maximum; });
        }

        $number = $this->faker->randomElement($numbers);

        return floatval($number) / 100;
    }

    private function buildSuite(int $minimum, int $maximum, int $multipleOf): array
    {
        $suite = [];

        $minimum = $this->goToNearestMultiple($minimum, $multipleOf);
        $number = $minimum;

        do {
            $suite[] = $number;

            $number = $this->goToNextMultiple($number, $multipleOf);
        } while ($number <= $maximum);

        return $suite;
    }

    private function goToNearestMultiple(int $n, int $x): int
    {
        return (round($n) % $x === 0)
            ? round($n)
            : round(($n + $x / 2) / $x) * $x
        ;
    }

    private function goToNextMultiple(int $n, int $x): int
    {
        return round(($n + $x / 2) / $x) * $x;
    }
}
