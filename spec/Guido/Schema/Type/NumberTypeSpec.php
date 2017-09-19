<?php

namespace spec\Guido\Schema\Type;

use Guido\Schema\Type\NumberType;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use stdClass;
use DateTime;
use Guido\Bogus;
use Guido\Schema\Type;

class NumberTypeSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(NumberType::class);
        $this->shouldImplement(Type::class);
        $this->shouldImplement(Bogus::class);
    }

    public function it_generates_numbers()
    {
        $schema = new stdClass;

        $this->generate($schema)->shouldBeFloat();
    }

    public function it_generates_numbers_following_a_multiple()
    {
        $schema = new stdClass;
        $schema->multipleOf = 3;

        $number = $this->generate($schema);
        $number->shouldBeFloat();
        $number->shouldBeMultipleOf(3.0);
    }

    public function it_generates_numbers_between_limits()
    {
        $schema = new stdClass;
        $schema->minimum = 3;

        $number = $this->generate($schema);
        $number->shouldBeFloat();
        $number->shouldBeUpperOrEqualTo(3.0);

        $schema = new stdClass;
        $schema->maximum = 3;

        $number = $this->generate($schema);
        $number->shouldBeFloat();
        $number->shouldBeLowerOrEqualTo(3.0);

        $schema = new stdClass;
        $schema->minimum = 3;
        $schema->maximum = 9;

        $number = $this->generate($schema);
        $number->shouldBeFloat();
        $number->shouldBeUpperOrEqualTo(3.0);
        $number->shouldBeLowerOrEqualTo(9.0);
    }

    public function it_generates_numbers_between_excluded_limits()
    {
        $schema = new stdClass;
        $schema->minimum = 3;
        $schema->exclusiveMinimum = true;

        $number = $this->generate($schema);
        $number->shouldBeFloat();
        $number->shouldBeUpperThan(3.0);

        $schema = new stdClass;
        $schema->maximum = 3;
        $schema->exclusiveMaximum = true;

        $number = $this->generate($schema);
        $number->shouldBeFloat();
        $number->shouldBeLowerThan(3.0);

        $schema = new stdClass;
        $schema->minimum = 3;
        $schema->maximum = 9;
        $schema->exclusiveMinimum = true;
        $schema->exclusiveMaximum = true;

        $number = $this->generate($schema);
        $number->shouldBeFloat();
        $number->shouldBeUpperThan(3.0);
        $number->shouldBeLowerThan(9.0);
    }

    public function getMatchers()
    {
        return [
            'beMultipleOf' => function (float $number, float $multiple) {
                return 0 === ($number % $multiple);
            },
            'beUpperThan' => function (float $number, float $min) {
                return $number > $min;
            },
            'beLowerThan' => function (float $number, float $max) {
                return $number < $max;
            },
            'beUpperOrEqualTo' => function (float $number, float $min) {
                return $number >= $min;
            },
            'beLowerOrEqualTo' => function (float $number, float $max) {
                return $number <= $max;
            },
        ];
    }
}
