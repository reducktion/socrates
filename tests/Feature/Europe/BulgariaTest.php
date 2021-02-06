<?php

namespace Reducktion\Socrates\Tests\Feature\Europe;

use DateTime;
use Carbon\Carbon;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Laravel\Facades\Socrates;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Tests\Feature\FeatureTest;

class BulgariaTest extends FeatureTest
{
    private $people;
    private $invalidIds;

    protected function setUp(): void
    {
        parent::setUp();

        $this->people = [
            'Andrei' => [
                'egn' => '7523169263',
                'gender' => Gender::MALE,
                'dob' => new DateTime('1875-03-16'),
                'age' => $this->calculateAge(new DateTime('1875-03-16')),
            ],
            'Lyuben' => [
                'egn' => '8032056031',
                'gender' => Gender::MALE,
                'dob' => new DateTime('1880-12-05'),
                'age' => $this->calculateAge(new DateTime('1880-12-05')),
            ],
            'Bilyana' => [
                'egn' => '8001010008',
                'gender' => Gender::FEMALE,
                'dob' => new DateTime('1980-01-01'),
                'age' => $this->calculateAge(new DateTime('1980-01-01')),
            ],
            'Kalina' => [
                'egn' => '7501020018',
                'gender' => Gender::FEMALE,
                'dob' => new DateTime('1975-01-02'),
                'age' => $this->calculateAge(new DateTime('1975-01-02')),
            ],
            'Nedyalko' => [
                'egn' => '7552010005',
                'gender' => Gender::MALE,
                'dob' => new DateTime('2075-12-01'),
                'age' => $this->calculateAge(new DateTime('2075-12-01')),
            ],
            'Tsveta' => [
                'egn' => '7542011030',
                'gender' => Gender::FEMALE,
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
            $citizen = Socrates::getCitizenDataFromId($person['egn'], 'BG');
            self::assertEquals($person['gender'], $citizen->getGender());
            self::assertEquals(Carbon::instance($person['dob']), $citizen->getDateOfBirth());
            self::assertEquals($person['dob'], $citizen->getDateOfBirthNative());
            self::assertEquals($person['age'], $citizen->getAge());
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::getCitizenDataFromId('754201103', 'BG');
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->people as $person) {
            self::assertTrue(
                Socrates::validateId($person['egn'], 'BG')
            );
        }

        foreach ($this->invalidIds as $egn) {
            self::assertFalse(
                Socrates::validateId($egn, 'BG')
            );
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::validateId('754201103', 'BG');
    }
}
