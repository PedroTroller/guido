<?php

namespace spec\Guido\Schema\Type;

use Guido\Schema\Type\NullType;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use stdClass;
use DateTime;
use Guido\Bogus;
use Guido\Schema\Type;

class NullTypeSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(NullType::class);
        $this->shouldImplement(Type::class);
        $this->shouldImplement(Bogus::class);
    }

    public function it_generates_null_values()
    {
        $schema = new stdClass;

        $this->generate($schema)->shouldBeNull();
    }
}
