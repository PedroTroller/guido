<?php

namespace spec\Guido\Schema\Type;

use Guido\Schema\Type\ArrayType;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use stdClass;
use DateTime;
use Guido\Bogus;
use Guido\Schema\Type;
use Guido\Bogus\Builder;

class ArrayTypeSpec extends ObjectBehavior
{
    public function let(Builder $builder)
    {
        $this->beConstructedWith($builder);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ArrayType::class);
        $this->shouldImplement(Type::class);
        $this->shouldImplement(Bogus::class);
    }

    public function it_generates_an_array()
    {
        $schema = new stdClass;

        $this->generate($schema)->shouldBeArray();
    }

    public function it_generates_an_array_with_limits_of_items()
    {
        $schema = new stdClass;
        $schema->minItems = 3;

        $array = $this->generate($schema);
        $array->shouldBeArray();
        $array->shouldHaveLengthBiggerOrEqualsTo(3);

        $schema = new stdClass;
        $schema->maxItems = 3;

        $array = $this->generate($schema);
        $array->shouldBeArray();
        $array->shouldHaveLengthSmallerOrEqualsTo(3);

        $schema = new stdClass;
        $schema->minItems = 3;
        $schema->maxItems = 6;

        $array = $this->generate($schema);
        $array->shouldBeArray();
        $array->shouldHaveLengthBiggerOrEqualsTo(3);
        $array->shouldHaveLengthSmallerOrEqualsTo(6);
    }

    public function it_generates_an_array_of_a_specific_type($builder, $subSchema, $type)
    {
        $schema = new stdClass;
        $schema->items = $subSchema;

        $builder->build($subSchema)->willReturn($type);

        $array = $this->generate($schema);
        $array->shouldBeArray();
        $array->shouldHaveLengthBiggerOrEqualsTo(1);

        foreach ($array as $item) {
            $item->shouldBe($schema);
        }
    }

    public function getMatchers()
    {
        return [
            'haveLengthBiggerOrEqualsTo' => function (array $array, int $min) {
                return count($array) >= $min;
            },
            'haveLengthSmallerOrEqualsTo' => function (array $array, int $max) {
                return count($array) <= $max;
            },
        ];
    }
}
