<?php

namespace Reducktion\Socrates\Tests\Feature\Europe;

use DateTime;
use Reducktion\Socrates\Constants\Country;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Tests\Feature\FeatureTest;

class NorwayTest extends FeatureTest
{
    private array $people;
    private array $invalidIds;

    protected function setUp(): void
    {
        parent::setUp();

        $this->people = [
            'kristoffer' => [
                'fn' => '05080176785',
                'gender' => Gender::Male,
                'dob' => new DateTime('2001-08-05'),
                'age' => $this->calculateAge(new DateTime('2001-08-05')),
            ],
            'astrid' => [
                'fn' => '20050761232',
                'gender' => Gender::Female,
                'dob' => new DateTime('2007-05-20'),
                'age' => $this->calculateAge(new DateTime('2007-05-20')),
            ],
            'linn' => [
                'fn' => '28094949248',
                'gender' => Gender::Female,
                'dob' => new DateTime('1949-09-28'),
                'age' => $this->calculateAge(new DateTime('1949-09-28')),
            ],
            'terje' => [
                'fn' => '14019513913',
                'gender' => Gender::Male,
                'dob' => new DateTime('1995-01-14'),
                'age' => $this->calculateAge(new DateTime('1995-01-14')),
            ],
            'heidi' => [
                'fn' => '01090749036',
                'gender' => Gender::Female,
                'dob' => new DateTime('1907-09-01'),
                'age' => $this->calculateAge(new DateTime('1907-09-01')),
            ],
        ];

        $this->invalidIds = [
            '23432124549',
            '33369400349',
            '08838323133',
            '13323213237',
            '04040470548'
        ];
    }

    public function test_extract_behaviour(): void
    {
        foreach ($this->people as $person) {
            $citizen = $this->socrates->getCitizenDataFromId($person['fn'], Country::Norway);

            self::assertEquals($person['gender'], $citizen->getGender());
            self::assertEquals($person['dob'], $citizen->getDateOfBirth());
            self::assertEquals($person['age'], $citizen->getAge());
        }

        $this->expectException(InvalidLengthException::class);

        $this->socrates->getCitizenDataFromId('324432343', Country::Norway);
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->people as $person) {
            self::assertTrue(
                $this->socrates->validateId($person['fn'], Country::Norway)
            );
        }

        foreach ($this->invalidIds as $fn) {
            self::assertFalse(
                $this->socrates->validateId($fn, Country::Norway)
            );
        }

        $this->expectException(InvalidLengthException::class);

        $this->socrates->validateId('214422411', Country::Norway);
    }
}
