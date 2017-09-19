<?php

namespace spec\Guido\Schema\Type;

use Guido\Schema\Type\ObjectType;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Guido\Bogus\Builder;
use Guido\Bogus\Builder\Context;
use stdClass;
use Guido\Bogus;

class ObjectTypeSpec extends ObjectBehavior
{
    function let(Builder $builder, Context $context)
    {
        $builder->getContext()->willReturn($context);

        $this->beConstructedWith($builder);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ObjectType::class);
    }

    public function it_generates_an_object($context)
    {
        $schema = new stdClass;

        $context->getGenerationPolicy()->willReturn(Context::OBJECT_REQUIRED_PROPERTIES);

        $this->generate($schema)->shouldHaveType(stdClass::class);
    }

    public function it_generates_an_array_with_the_minimum_set_of_properties($builder, $context, Bogus $identifierBogus)
    {
        $identifierSchema = new stdClass;
        $nameSchema = new stdClass;

        $schema = new stdClass;
        $schema->properties = (object) [
            'identifier' => $identifierSchema,
            'name'       => $nameSchema,
        ];
        $schema->required = ['identifier'];

        $builder->build($identifierSchema)->willReturn($identifierBogus);

        $identifierBogus->generate($identifierSchema)->willReturn('the foo');

        $context->getGenerationPolicy()->willReturn(Context::OBJECT_REQUIRED_PROPERTIES);

        $object = $this->generate($schema);

        expect(count((array)$object->getWrappedObject()))->toBe(1);
        expect(array_keys((array)$object->getWrappedObject()))->toBe(['identifier']);
        expect($object->getWrappedObject()->identifier)->toBe('the foo');
    }

    public function it_generates_an_array_with_the_full_set_of_properties($builder, $context, Bogus $identifierBogus, Bogus $nameBogus)
    {
        $identifierSchema = new stdClass;
        $identifierSchema->type = 'number';
        $nameSchema = new stdClass;
        $nameSchema->type = 'string';

        $schema = new stdClass;
        $schema->properties = (object) [
            'identifier' => $identifierSchema,
            'name'       => $nameSchema,
        ];
        $schema->required = ['identifier'];

        $builder->build($identifierSchema)->willReturn($identifierBogus);
        $builder->build($nameSchema)->willReturn($nameBogus);

        $identifierBogus->generate($identifierSchema)->willReturn('the foo');
        $nameBogus->generate($nameSchema)->willReturn('the bar');

        $context->getGenerationPolicy()->willReturn(Context::OBJECT_ALL_PROPERTIES);

        $object = $this->generate($schema);

        expect(count((array)$object->getWrappedObject()))->toBe(2);
        expect(array_keys((array)$object->getWrappedObject()))->toBe(['identifier', 'name']);
        expect($object->getWrappedObject()->identifier)->toBe('the foo');
        expect($object->getWrappedObject()->name)->toBe('the bar');
    }
}
