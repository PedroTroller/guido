<?php

namespace Guido\Bogus;

use stdClass;
use Guido\Schema\Combination;
use Guido\Schema\Type;
use Guido\Bogus\Builder\Context;
use Guido\Schema\Reference;

class Builder
{
    /**
     * @var Context
     */
    private $context;

    /**
     * @var Reference\Local
     */
    private $localReference;

    public function __construct(Context $context = null)
    {
        $this->context = $context ?: new Context();
    }

    public function getContext(): Context
    {
        return $this->context;
    }

    public function build(stdClass $schema)
    {
        $this->localReference = $this->localReference ?: new Reference\Local($this, $schema);

        // References
        $refKey = '$ref';
        if (isset($schema->$refKey)) {
            return $this->localReference;
        }

        // Types
        if (isset($schema->type)) {
            switch ($schema->type) {
                case 'array':   return new Type\ArrayType($this);
                case 'boolean': return new Type\BooleanType;
                case 'integer': return new Type\IntegerType;
                case 'null':    return new Type\NullType;
                case 'number':  return new Type\NumberType;
                case 'object':  return new Type\ObjectType($this);
                case 'string':  return new Type\StringType;
            }
        }

        // Combinations
        if (isset($schema->anyOf)) {
            return new Combination\AnyOfCombination($this);
        }
    }
}
