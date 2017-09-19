<?php

namespace Guido\Schema\Type;

use Guido\Schema\Type;
use stdClass;
use Faker\Factory;
use DateTime;

class StringType implements Type
{
    /**
     * @var Generator
     */
    private $faker;

    public function __construct(Generator $faker = null)
    {
        $this->faker = $faker ?: Factory::create();
    }

    /**
     * {@inheritdoc}
     */
    public function generate(stdClass $schema): string
    {
        if (isset($schema->format) && null !== $format = $schema->format) {
            switch ($format) {
                case 'date-time':
                    return $this->faker->date(DateTime::RFC3339);
                case 'email':
                    return $this->faker->email;
                case 'hostname':
                    return $this->faker->domainName;
                case 'ipv4':
                    return $this->faker->ipv4;
                case 'ipv6':
                    return $this->faker->ipv6;
                case 'uri':
                    return $this->faker->url;
            }
        }

        $minLength = 1;

        if (isset($schema->minLength)) {
            $minLength = $schema->minLength;
        }

        $maxLength = $minLength > 0
            ? $minLength * 10
            : 10
        ;

        if (isset($schema->maxLength)) {
            $maxLength = $schema->maxLength;
        }

        $string = "";

        do {
            $string .= $this->faker->sentence;
        } while (strlen($string) < $maxLength);

        return substr($string, 0, $this->faker->numberBetween($minLength, $maxLength));
    }
}
