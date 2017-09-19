<?php

namespace spec\Guido\Bogus;

use Guido\Bogus\Builder;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use stdClass;
use Guido\Schema\Combination\AnyOfCombination;
use Guido\Schema\Type\StringType;
use Guido\Schema\Type\IntegerType;
use Guido\Schema\Type\NullType;
use Guido\Schema\Type\BooleanType;
use Guido\Schema\Type\NumberType;
use Guido\Schema\Type\ArrayType;
use Guido\Schema\Type\ObjectType;

class BuilderSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Builder::class);
    }

    public function it_builds_a_string_type()
    {
        $schema = new stdClass;
        $schema->type = 'string';

        $this->build($schema)->shouldHaveType(StringType::class);
    }

    public function it_builds_an_integer_type()
    {
        $schema = new stdClass;
        $schema->type = 'integer';

        $this->build($schema)->shouldHaveType(IntegerType::class);
    }

    public function it_builds_a_number_type()
    {
        $schema = new stdClass;
        $schema->type = 'number';

        $this->build($schema)->shouldHaveType(NumberType::class);
    }

    public function it_builds_a_boolean_type()
    {
        $schema = new stdClass;
        $schema->type = 'boolean';

        $this->build($schema)->shouldHaveType(BooleanType::class);
    }

    public function it_builds_a_null_type()
    {
        $schema = new stdClass;
        $schema->type = 'null';

        $this->build($schema)->shouldHaveType(NullType::class);
    }

    public function it_builds_an_array_type()
    {
        $schema = new stdClass;
        $schema->type = 'array';

        $this->build($schema)->shouldHaveType(ArrayType::class);
    }

    public function it_builds_an_object_type()
    {
        $schema = new stdClass;
        $schema->type = 'object';

        $this->build($schema)->shouldHaveType(ObjectType::class);
    }

    public function it_builds_an_any_of_combination()
    {
        $schema = new stdClass;
        $schema->anyOf = [];

        $this->build($schema)->shouldHaveType(AnyOfCombination::class);
    }
}
