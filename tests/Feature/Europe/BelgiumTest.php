<?php

namespace Reducktion\Socrates\Tests\Feature\Europe;

use DateTime;
use Carbon\Carbon;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Laravel\Facades\Socrates;
use Reducktion\Socrates\Tests\Feature\FeatureTest;

class BelgiumTest extends FeatureTest
{
    private $people;
    private $invalidIds;

    public function setUp(): void
    {
        parent::setUp();

        $this->people = [
            'shahin' => [
                'id' => '93.05.18-223.61',
                'gender' => Gender::MALE,
                'dob' => new DateTime('1993-05-18'),
                'age' => $this->calculateAge(new DateTime('1993-05-18')),
            ],
            'naoual' => [
                'id' => '730111-361-73',
                'gender' => Gender::MALE,
                'dob' => new DateTime('1973-01-11'),
                'age' => $this->calculateAge(new DateTime('1973-01-11')),
            ],
            'xavi' => [
                'id' => '75.12.05-137.14',
                'gender' => Gender::MALE,
                'dob' => new DateTime('1975-12-05'),
                'age' => $this->calculateAge(new DateTime('1975-12-05')),
            ],
            'kurt' => [
                'id' => '71.09.07-213.64',
                'gender' => Gender::MALE,
                'dob' => new DateTime('1971-09-07'),
                'age' => $this->calculateAge(new DateTime('1971-09-07')),
            ],
            'mark' => [
                'id' => '40.00.01-001.33',
                'gender' => Gender::MALE,
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
            '09.08.24-282.48', // invalid age
            '01.11.16-000.06', // invalid sequence number
            '01.11.16-999.74'  // invalid sequence number
        ];
    }

    public function test_extract_behaviour(): void
    {
        foreach ($this->people as $person) {
            $citizen = Socrates::getCitizenDataFromId($person['id'], 'BE');
            self::assertEquals($person['gender'], $citizen->getGender());
            self::assertEquals(Carbon::instance($person['dob']), $citizen->getDateOfBirth());
            self::assertEquals($person['dob'], $citizen->getDateOfBirthNative());
            self::assertEquals($person['age'], $citizen->getAge());
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::getCitizenDataFromId('12.12.12-1323.32', 'BE');
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->people as $person) {
            self::assertTrue(
                Socrates::validateId($person['id'], 'BE')
            );
        }

        foreach ($this->invalidIds as $id) {
            self::assertFalse(
                Socrates::validateId($id, 'BE')
            );
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::getCitizenDataFromId('12.12.12-1323.32', 'BE');
    }
}
