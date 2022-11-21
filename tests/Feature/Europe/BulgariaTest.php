<?php

namespace Reducktion\Socrates\Tests\Feature\Europe;

use DateTime;
use Reducktion\Socrates\Constants\Country;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Tests\Feature\FeatureTest;

class BulgariaTest extends FeatureTest
{
    private array $people;
    private array $invalidIds;

    protected function setUp(): void
    {
        parent::setUp();

        $this->people = [
            'Andrei' => [
                'egn' => '7523169263',
                'gender' => Gender::Male,
                'dob' => new DateTime('1875-03-16'),
                'age' => $this->calculateAge(new DateTime('1875-03-16')),
            ],
            'Lyuben' => [
                'egn' => '8032056031',
                'gender' => Gender::Male,
                'dob' => new DateTime('1880-12-05'),
                'age' => $this->calculateAge(new DateTime('1880-12-05')),
            ],
            'Bilyana' => [
                'egn' => '8001010008',
                'gender' => Gender::Female,
                'dob' => new DateTime('1980-01-01'),
                'age' => $this->calculateAge(new DateTime('1980-01-01')),
            ],
            'Kalina' => [
                'egn' => '7501020018',
                'gender' => Gender::Female,
                'dob' => new DateTime('1975-01-02'),
                'age' => $this->calculateAge(new DateTime('1975-01-02')),
            ],
            'Nedyalko' => [
                'egn' => '7552010005',
                'gender' => Gender::Male,
                'dob' => new DateTime('2075-12-01'),
                'age' => $this->calculateAge(new DateTime('2075-12-01')),
            ],
            'Tsveta' => [
                'egn' => '7542011030',
                'gender' => Gender::Female,
                'dob' => new DateTime('2075-02-01'),
                'age' => $this->calculateAge(new DateTime('2075-02-01')),
            ]
        ];

        $this->invalidIds = [
            '7542021030',
            '8002560008',
            '3542027033',
            '6002567498',
            '7542039611',
        ];
    }

    public function test_extract_behaviour(): void
    {
        foreach ($this->people as $person) {
            $citizen = $this->socrates->getCitizenDataFromId($person['egn'], Country::Bulgaria);
            self::assertEquals($person['gender'], $citizen->getGender());
            self::assertEquals($person['dob'], $citizen->getDateOfBirth());
            self::assertEquals($person['age'], $citizen->getAge());
        }

        $this->expectException(InvalidLengthException::class);

        $this->socrates->getCitizenDataFromId('754201103', Country::Bulgaria);
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->people as $person) {
            self::assertTrue(
                $this->socrates->validateId($person['egn'], Country::Bulgaria)
            );
        }

        foreach ($this->invalidIds as $egn) {
            self::assertFalse(
                $this->socrates->validateId($egn, Country::Bulgaria)
            );
        }

        $this->expectException(InvalidLengthException::class);

        $this->socrates->validateId('754201103', Country::Bulgaria);
    }
}
