<?php

namespace spec\Guido\Schema\Reference;

use Guido\Schema\Reference\Local;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use stdClass;
use Guido\Bogus\Builder;
use Guido\Bogus;

class LocalSpec extends ObjectBehavior
{
    /**
     * @var stdClass
     */
    private $root;

    function let(Builder $builder)
    {
        $this->root = new StdClass;
        $this->root->definitions = new stdClass;

        $this->beConstructedWith($builder, $this->root);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Local::class);
    }

    public function it_resolves_the_references($builder, Bogus $fooBogus)
    {
        $fooSchema = new stdClass;
        $this->root->definitions->Foo = $fooSchema;

        $builder->build($fooSchema)->willReturn($fooBogus);

        $fooBogus->generate($fooSchema)->willReturn('the foo');

        $schema = new stdClass;

        $refKey = '$ref';

        $schema->$refKey = '#/definitions/Foo';

        $this->generate($schema)->shouldReturn('the foo');
    }
}
