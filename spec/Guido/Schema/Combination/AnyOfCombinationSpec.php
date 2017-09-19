<?php

namespace spec\Guido\Schema\Combination;

use Guido\Schema\Combination\AnyOfCombination;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Guido\Bogus\Builder;
use stdClass;
use Guido\Bogus;

class AnyOfCombinationSpec extends ObjectBehavior
{
    function let(Builder $builder)
    {
        $this->beConstructedWith($builder);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(AnyOfCombination::class);
    }

    function it_can_generate_data_from_any_of_types($builder, Bogus $generator1, Bogus $generator2)
    {
        $schema1 = new stdClass;
        $schema2 = new stdClass;

        $schema = new stdClass;
        $schema->anyOf = [$schema1, $schema2];

        $builder->build($schema1)->willReturn($generator1);
        $builder->build($schema2)->willReturn($generator2);

        $generator1->generate($schema1)->willReturn('foo');
        $generator2->generate($schema2)->willReturn('bar');

        $this->generate($schema)->shouldReturnOneOf(['foo', 'bar']);
    }

    public function getMatchers()
    {
        return [
            'returnOneOf' => function ($subject, $key) {
                return false !== array_search($subject, $key);
            },
        ];
    }
}
