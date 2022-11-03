<?php

namespace Reducktion\Socrates\Tests\Feature\Europe;

use DateTime;
use Reducktion\Socrates\Constants\Country;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Tests\Feature\FeatureTest;

class HungaryTest extends FeatureTest
{
    private array $people;
    private array $invalidIds;

    protected function setUp(): void
    {
        parent::setUp();

        $this->people = [
            'Aliz' => [
                'pin' => '2-720216-1673',
                'gender' => Gender::Female,
                'dob' => new DateTime('1972-02-16'),
                'age' => $this->calculateAge(new DateTime('1972-02-16')),
            ],
            'Dora' => [
                'pin' => '2-690609-5528',
                'gender' => Gender::Female,
                'dob' => new DateTime('1969-06-09'),
                'age' => $this->calculateAge(new DateTime('1969-06-09')),
            ],
            'Jolan' => [
                'pin' => '2-840320-0414',
                'gender' => Gender::Female,
                'dob' => new DateTime('1984-03-20'),
                'age' => $this->calculateAge(new DateTime('1984-03-20')),
            ],
            'Kapolcs' => [
                'pin' => '3-101010-5646',
                'gender' => Gender::Male,
                'dob' => new DateTime('2010-10-10'),
                'age' => $this->calculateAge(new DateTime('2010-10-10')),
            ],
            'Vincze' => [
                'pin' => '3-080321-8523',
                'gender' => Gender::Male,
                'dob' => new DateTime('2008-03-21'),
                'age' => $this->calculateAge(new DateTime('2008-03-21')),
            ],
        ];

        $this->invalidIds = [];
    }

    public function test_extract_behaviour(): void
    {
        foreach ($this->people as $person) {
            $citizen = $this->socrates->getCitizenDataFromId($person['pin'], Country::Hungary);
            self::assertEquals($person['gender'], $citizen->getGender());
            self::assertEquals($person['dob'], $citizen->getDateOfBirth());
            self::assertEquals($person['age'], $citizen->getAge());
        }

        $this->expectException(InvalidLengthException::class);

        $this->socrates->getCitizenDataFromId('26905528', Country::Hungary);
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->people as $person) {
            self::assertTrue(
                $this->socrates->validateId($person['pin'], Country::Hungary)
            );
        }

        foreach ($this->invalidIds as $pin) {
            self::assertFalse(
                $this->socrates->validateId($pin, Country::Bulgaria)
            );
        }

        $this->expectException(InvalidLengthException::class);

        $this->socrates->validateId('26905528', Country::Hungary);
    }
}
