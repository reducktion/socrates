<?php

namespace Reducktion\Socrates\Tests\Feature\Europe;

use DateTime;
use Reducktion\Socrates\Constants\Country;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Tests\Feature\FeatureTest;

class FinlandTest extends FeatureTest
{
    private array $people;
    private array $invalidIds;

    protected function setUp(): void
    {
        parent::setUp();

        $this->people = [
            'senja' => [
                'hetu' => '040560-600E',
                'gender' => Gender::Female,
                'dob' => new DateTime('1960-05-04'),
                'age' => $this->calculateAge(new DateTime('1960-05-04')),
            ],
            'elias' => [
                'hetu' => '121093-275N',
                'gender' => Gender::Male,
                'dob' => new DateTime('1993-10-12'),
                'age' => $this->calculateAge(new DateTime('1993-10-12')),
            ],
            'ida' => [
                'hetu' => '260555-512H',
                'gender' => Gender::Female,
                'dob' => new DateTime('1955-05-26'),
                'age' => $this->calculateAge(new DateTime('1955-05-26')),
            ],
            'iiro' => [
                'hetu' => '110416A479W',
                'gender' => Gender::Male,
                'dob' => new DateTime('2016-04-11'),
                'age' => $this->calculateAge(new DateTime('2016-04-11')),
            ],
            'stig' => [
                'hetu' => '040403A2676',
                'gender' => Gender::Male,
                'dob' => new DateTime('2003-04-04'),
                'age' => $this->calculateAge(new DateTime('2003-04-04')),
            ]
        ];

        $this->invalidIds = [
            '050403+2676',
            '110414-479W',
            '653416A549B',
            '122417-456T',
            '121212A479F'
        ];
    }

    public function test_extract_behaviour(): void
    {
        foreach ($this->people as $person) {
            $citizen = $this->socrates->getCitizenDataFromId($person['hetu'], Country::Finland);

            self::assertEquals($person['gender'], $citizen->getGender());
            self::assertEquals($person['dob'], $citizen->getDateOfBirth());
            self::assertEquals($person['age'], $citizen->getAge());
        }

        $this->expectException(InvalidLengthException::class);

        $this->socrates->getCitizenDataFromId('04403A2676', Country::Finland);
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->people as $person) {
            self::assertTrue(
                $this->socrates->validateId($person['hetu'], Country::Finland)
            );
        }

        foreach ($this->invalidIds as $hetu) {
            self::assertFalse(
                $this->socrates->validateId($hetu, Country::Finland)
            );
        }

        $this->expectException(InvalidLengthException::class);

        $this->socrates->validateId('21344-6451', Country::Finland);
    }
}
