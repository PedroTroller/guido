<?php

namespace spec\Guido\Schema\Type;

use Guido\Schema\Type\StringType;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use stdClass;
use DateTime;
use Guido\Bogus;
use Guido\Schema\Type;

class StringTypeSpec extends ObjectBehavior
{
    const EMAIL_RFC_5322 = '/^(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){255,})(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){65,}@)(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22))(?:\.(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-[a-z0-9]+)*\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-[a-z0-9]+)*)|(?:\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\]))$/iD';

    const HOSTNAME_RFC_1034 = '/^(([a-zA-Z0-9]|[a-zA-Z0-9][a-zA-Z0-9\-]*[a-zA-Z0-9])\.)*([A-Za-z0-9]|[A-Za-z0-9][A-Za-z0-9\-]*[A-Za-z0-9])$/';

    const IPV4_RFC_2673 = '/^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?).(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?).(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?).(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/';

    const IPV6_RFC_2373 = '/^((([0-9A-Fa-f]{1,4}:){7}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){6}:[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){5}:([0-9A-Fa-f]{1,4}:)?[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){4}:([0-9A-Fa-f]{1,4}:){0,2}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){3}:([0-9A-Fa-f]{1,4}:){0,3}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){2}:([0-9A-Fa-f]{1,4}:){0,4}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){6}((b((25[0-5])|(1d{2})|(2[0-4]d)|(d{1,2}))b).){3}(b((25[0-5])|(1d{2})|(2[0-4]d)|(d{1,2}))b))|(([0-9A-Fa-f]{1,4}:){0,5}:((b((25[0-5])|(1d{2})|(2[0-4]d)|(d{1,2}))b).){3}(b((25[0-5])|(1d{2})|(2[0-4]d)|(d{1,2}))b))|(::([0-9A-Fa-f]{1,4}:){0,5}((b((25[0-5])|(1d{2})|(2[0-4]d)|(d{1,2}))b).){3}(b((25[0-5])|(1d{2})|(2[0-4]d)|(d{1,2}))b))|([0-9A-Fa-f]{1,4}::([0-9A-Fa-f]{1,4}:){0,5}[0-9A-Fa-f]{1,4})|(::([0-9A-Fa-f]{1,4}:){0,6}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){1,7}:))$/';

    const URI_RFC_3986 = '/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/';

    function it_is_initializable()
    {
        $this->shouldHaveType(StringType::class);
        $this->shouldImplement(Type::class);
        $this->shouldImplement(Bogus::class);
    }

    public function it_generates_strings()
    {
        $schema = new stdClass;

        $this->generate($schema)->shouldBeString();
    }

    public function it_generates_strings_with_a_minimum_length()
    {
        $schema = new stdClass;
        $schema->minLength = 10;

        $string = $this->generate($schema);

        $string->shouldBeString();
        $string->shouldBeLongerThan(9);
    }

    public function it_generates_strings_with_a_maximum_length()
    {
        $schema = new stdClass;
        $schema->maxLength = 10;

        $string = $this->generate($schema);

        $string->shouldBeString();
        $string->shouldBeShorterThan(11);
    }

    public function it_generates_strings_with_both_minimum_and_maximum_length()
    {
        $schema = new stdClass;
        $schema->minLength = 10;
        $schema->maxLength = 20;

        $string = $this->generate($schema);

        $string->shouldBeString();
        $string->shouldBeLongerThan(9);
        $string->shouldBeShorterThan(21);
    }

    /**
     * @todo not implemented
     */
    public function it_generates_strings_following_a_regex()
    {
        $schema = new stdClass;
        $schema->pattern = '\w.';

        $this->generate($schema)->shouldBeString();
    }

    public function it_generates_strings_following_a_format()
    {
        $schema = new stdClass;
        $schema->format = 'date-time';

        $this->generate($schema)->shouldBeAValidRFC3339();

        $schema = new stdClass;
        $schema->format = 'email';

        $this->generate($schema)->shouldBeAValidEmail();

        $schema = new stdClass;
        $schema->format = 'hostname';

        $this->generate($schema)->shouldBeAValidHostname();

        $schema = new stdClass;
        $schema->format = 'ipv4';

        $this->generate($schema)->shouldBeAValidIPV4();

        $schema = new stdClass;
        $schema->format = 'ipv6';

        $this->generate($schema)->shouldBeAValidIPV6();

        $schema = new stdClass;
        $schema->format = 'uri';

        $this->generate($schema)->shouldBeAValidUri();
    }

    public function getMatchers()
    {
        return [
            'beShorterThan' => function ($string, $max) {
                return strlen($string) < $max;
            },
            'beLongerThan' => function ($string, $min) {
                return strlen($string) > $min;
            },
            'beAValidRFC3339' => function ($string) {
                $date = DateTime::createFromFormat(DateTime::RFC3339, $string);

                if (false === $date) {
                    return false;
                }

                return $string === $date->format(DateTime::RFC3339);
            },
            'beAValidEmail' => function ($string) {
                return 1 === preg_match(self::EMAIL_RFC_5322, $string);
            },
            'beAValidHostname' => function ($string) {
                return 1 === preg_match(self::HOSTNAME_RFC_1034, $string);
            },
            'beAValidIPV4' => function ($string) {
                return 1 === preg_match(self::IPV4_RFC_2673, $string);
            },
            'beAValidIPV6' => function ($string) {
                return 1 === preg_match(self::IPV6_RFC_2373, $string);
            },
            'beAValidUri' => function ($string) {
                return 1 === preg_match(self::URI_RFC_3986, $string);
            },
        ];
    }
}
