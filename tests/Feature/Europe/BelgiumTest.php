<?php

namespace Reducktion\Socrates\Tests\Feature\Europe;

use DateTime;
use Reducktion\Socrates\Constants\Country;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Tests\Feature\FeatureTest;

class BelgiumTest extends FeatureTest
{
    private array $people;
    private array $invalidIds;

    public function setUp(): void
    {
        parent::setUp();

        $this->people = [
            'shahin' => [
                'id' => '93.05.18-223.61',
                'gender' => Gender::Male,
                'dob' => new DateTime('1993-05-18'),
                'age' => $this->calculateAge(new DateTime('1993-05-18')),
            ],
            'naoual' => [
                'id' => '730111-361-73',
                'gender' => Gender::Male,
                'dob' => new DateTime('1973-01-11'),
                'age' => $this->calculateAge(new DateTime('1973-01-11')),
            ],
            'xavi' => [
                'id' => '75.12.05-137.14',
                'gender' => Gender::Male,
                'dob' => new DateTime('1975-12-05'),
                'age' => $this->calculateAge(new DateTime('1975-12-05')),
            ],
            'kurt' => [
                'id' => '71.09.07-213.64',
                'gender' => Gender::Male,
                'dob' => new DateTime('1971-09-07'),
                'age' => $this->calculateAge(new DateTime('1971-09-07')),
            ],
            'mark' => [
                'id' => '40.00.01-001.33',
                'gender' => Gender::Male,
                'dob' => new DateTime('1940-01-01'),
                'age' => $this->calculateAge(new DateTime('1940-01-01')),
            ]
        ];

        $this->invalidIds = [
            '12.12.12-132.32',
            '97.12.03-123.12',
            '01.06.18-468.99',
            '64.04.09-874.43',
            '12.10.23-954.11',
            '00.08.24-282.48', // invalid age
            '01.11.16-000.06', // invalid sequence number
            '01.11.16-999.74'  // invalid sequence number
        ];
    }

    public function test_extract_behaviour(): void
    {
        foreach ($this->people as $person) {
            $citizen = $this->socrates->getCitizenDataFromId($person['id'], Country::Belgium);
            self::assertEquals($person['gender'], $citizen->getGender());
            self::assertEquals($person['dob'], $citizen->getDateOfBirth());
            self::assertEquals($person['age'], $citizen->getAge());
        }

        $this->expectException(InvalidLengthException::class);

        $this->socrates->getCitizenDataFromId('12.12.12-1323.32', Country::Belgium);
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->people as $person) {
            self::assertTrue(
                $this->socrates->validateId($person['id'], Country::Belgium)
            );
        }

        foreach ($this->invalidIds as $id) {
            self::assertFalse(
                $this->socrates->validateId($id, Country::Belgium)
            );
        }

        $this->expectException(InvalidLengthException::class);

        $this->socrates->getCitizenDataFromId('12.12.12-1323.32', Country::Belgium);
    }
}
