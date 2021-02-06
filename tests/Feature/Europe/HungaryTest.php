<?php

namespace Reducktion\Socrates\Tests\Feature\Europe;

use Carbon\Carbon;
use DateTime;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Laravel\Facades\Socrates;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Tests\Feature\FeatureTest;

class HungaryTest extends FeatureTest
{
    private $people;
    private $invalidIds;

    protected function setUp(): void
    {
        parent::setUp();

        $this->people = [
            'Aliz' => [
                'pin' => '2-720216-1673',
                'gender' => Gender::FEMALE,
                'dob' => new DateTime('1972-02-16'),
                'age' => $this->calculateAge(new DateTime('1972-02-16')),
            ],
            'Dora' => [
                'pin' => '2-690609-5528',
                'gender' => Gender::FEMALE,
                'dob' => new DateTime('1969-06-09'),
                'age' => $this->calculateAge(new DateTime('1969-06-09')),
            ],
            'Jolan' => [
                'pin' => '2-840320-0414',
                'gender' => Gender::FEMALE,
                'dob' => new DateTime('1984-03-20'),
                'age' => $this->calculateAge(new DateTime('1984-03-20')),
            ],
            'Kapolcs' => [
                'pin' => '3-101010-5646',
                'gender' => Gender::MALE,
                'dob' => new DateTime('2010-10-10'),
                'age' => $this->calculateAge(new DateTime('2010-10-10')),
            ],
            'Vincze' => [
                'pin' => '3-080321-8523',
                'gender' => Gender::MALE,
                'dob' => new DateTime('2008-03-21'),
                'age' => $this->calculateAge(new DateTime('2008-03-21')),
            ],
        ];

        $this->invalidIds = [];
    }

    public function test_extract_behaviour(): void
    {
        foreach ($this->people as $person) {
            $citizen = Socrates::getCitizenDataFromId($person['pin'], 'HU');
            self::assertEquals($person['gender'], $citizen->getGender());
            self::assertEquals(Carbon::instance($person['dob']), $citizen->getDateOfBirth());
            self::assertEquals($person['dob'], $citizen->getDateOfBirthNative());
            self::assertEquals($person['age'], $citizen->getAge());
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::getCitizenDataFromId('26905528', 'HU');
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->people as $person) {
            self::assertTrue(
                Socrates::validateId($person['pin'], 'HU')
            );
        }

        foreach ($this->invalidIds as $pin) {
            self::assertFalse(
                Socrates::validateId($pin, 'BG')
            );
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::validateId('26905528', 'HU');
    }
}
