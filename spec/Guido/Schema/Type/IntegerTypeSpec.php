<?php

namespace spec\Guido\Schema\Type;

use Guido\Schema\Type\IntegerType;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use stdClass;
use DateTime;
use Guido\Bogus;
use Guido\Schema\Type;

class IntegerTypeSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(IntegerType::class);
        $this->shouldImplement(Type::class);
        $this->shouldImplement(Bogus::class);
    }

    public function it_generates_numbers()
    {
        $schema = new stdClass;

        $this->generate($schema)->shouldBeInt();
    }

    public function it_generates_numbers_following_a_multiple()
    {
        $schema = new stdClass;
        $schema->multipleOf = 3;

        $number = $this->generate($schema);
        $number->shouldBeInt();
        $number->shouldBeMultipleOf(3);
    }

    public function it_generates_numbers_between_limits()
    {
        $schema = new stdClass;
        $schema->minimum = 3;

        $number = $this->generate($schema);
        $number->shouldBeInt();
        $number->shouldBeUpperOrEqualTo(3);

        $schema = new stdClass;
        $schema->maximum = 3;

        $number = $this->generate($schema);
        $number->shouldBeInt();
        $number->shouldBeLowerOrEqualTo(3);

        $schema = new stdClass;
        $schema->minimum = 3;
        $schema->maximum = 9;

        $number = $this->generate($schema);
        $number->shouldBeInt();
        $number->shouldBeUpperOrEqualTo(3);
        $number->shouldBeLowerOrEqualTo(9);
    }

    public function it_generates_numbers_between_excluded_limits()
    {
        $schema = new stdClass;
        $schema->minimum = 3;
        $schema->exclusiveMinimum = true;

        $number = $this->generate($schema);
        $number->shouldBeInt();
        $number->shouldBeUpperThan(3);

        $schema = new stdClass;
        $schema->maximum = 3;
        $schema->exclusiveMaximum = true;

        $number = $this->generate($schema);
        $number->shouldBeInt();
        $number->shouldBeLowerThan(3);

        $schema = new stdClass;
        $schema->minimum = 3;
        $schema->maximum = 9;
        $schema->exclusiveMinimum = true;
        $schema->exclusiveMaximum = true;

        $number = $this->generate($schema);
        $number->shouldBeInt();
        $number->shouldBeUpperThan(3);
        $number->shouldBeLowerThan(9);
    }

    public function getMatchers()
    {
        return [
            'beMultipleOf' => function (int $number, int $multiple) {
                return 0 === ($number % $multiple);
            },
            'beUpperThan' => function (int $number, int $min) {
                return $number > $min;
            },
            'beLowerThan' => function (int $number, int $max) {
                return $number < $max;
            },
            'beUpperOrEqualTo' => function (int $number, int $min) {
                return $number >= $min;
            },
            'beLowerOrEqualTo' => function (int $number, int $max) {
                return $number <= $max;
            },
        ];
    }
}
