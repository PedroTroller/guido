<?php

namespace spec\Guido\Schema\Type;

use Guido\Schema\Type\BooleanType;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use stdClass;
use DateTime;
use Guido\Bogus;
use Guido\Schema\Type;

class BooleanTypeSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(BooleanType::class);
        $this->shouldImplement(Type::class);
        $this->shouldImplement(Bogus::class);
    }

    public function it_generates_booleans()
    {
        $schema = new stdClass;

        $this->generate($schema)->shouldBeBoolean();
    }
}
