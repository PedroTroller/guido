<?php

namespace tests\Functionnal;

use stdClass;
use Guido\Bogus\Builder;
use JsonSchema;
use Exception;

class Basic implements \Funk\Spec
{
    public function it_can_generate_object_from_a_simple_schema()
    {
        $schema = new stdClass;

        $schema->type="object";

        $schema->required=['identifier', 'name', 'price'];

        $schema->properties = (object) [
            'identifier' => (function() {
                $schema = new stdClass;
                $schema->type = 'string';
                $schema->minLength = 100;
                $schema->maxLength = 100;

                return $schema;
            })(),
            'name' => (function() {
                $schema = new stdClass;
                $schema->type = 'string';

                return $schema;
            })(),
            'price' => (function() {
                $schema = new stdClass;
                $schema->type = 'object';
                $schema->required = ['amount', 'currency'];

                $schema->properties = [
                    'amount' => (function () {
                        $schema  = new stdClass;
                        $schema->type="number";
                        $schema->minimum=0;

                        return $schema;
                    })(),
                    'currency' => (function () {
                        $schema  = new stdClass;
                        $schema->type="string";
                        $schema->minLength=3;
                        $schema->maxLength=3;

                        return $schema;
                    })(),
                ];

                return $schema;
            })(),
        ];

        $object = (new Builder)->build($schema)->generate($schema);

        $validator = new JsonSchema\Validator;
        $validator->validate($object, $schema);

        if (false === $validator->isValid()) {
            foreach ($validator->getErrors() as $error) {
                throw new Exception(sprintf("[%s] %s\n", $error['property'], $error['message']));
            }
        }
    }

    public function it_can_generates_a_person()
    {
        $schema = new stdClass;

        $schema->type="object";

        $schema->required=['email', 'givenName', 'familyName', 'address'];

        $schema->properties = (object) [
            'givenName' => (function() {
                $schema = new stdClass;
                $schema->type = 'string';

                return $schema;
            })(),
            'familyName' => (function() {
                $schema = new stdClass;
                $schema->type = 'string';

                return $schema;
            })(),
            'email' => (function() {
                $schema = new stdClass;
                $schema->type = 'string';
                $schema->format = 'email';

                return $schema;
            })(),
            'address' => (function() {
                $refKey = '$ref';

                $schema = new stdClass;
                $schema->$refKey = '#/definitions/Address';

                return $schema;
            })(),
        ];

        $reference = new stdClass;

        $reference->type="object";

        $reference->required=['streetAddress', 'addressLocality', 'postalCode'];

        $reference->properties = (object) [
            'streetAddress' => (function() {
                $schema = new stdClass;
                $schema->type = 'string';

                return $schema;
            })(),
            'addressLocality' => (function() {
                $schema = new stdClass;
                $schema->type = 'string';

                return $schema;
            })(),
            'postalCode' => (function() {
                $schema = new stdClass;
                $schema->type = 'integer';
                $schema->minimum = '1000';
                $schema->maximum = '68000';

                return $schema;
            })(),
        ];

        $schema->definitions = (object) [
            'Address' => $reference,
        ];

        $object = (new Builder)->build($schema)->generate($schema);

        var_dump($object);

        $validator = new JsonSchema\Validator;
        $validator->validate($object, $schema);

        if (false === $validator->isValid()) {
            foreach ($validator->getErrors() as $error) {
                throw new Exception(sprintf("[%s] %s\n", $error['property'], $error['message']));
            }
        }
    }
}
