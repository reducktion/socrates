<?php

namespace Reducktion\Socrates\Tests\Feature\Europe;

use DateTime;
use Reducktion\Socrates\Constants\Country;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Tests\Feature\FeatureTest;

class SwedenTest extends FeatureTest
{
    private array $people;
    private array $invalidIds;

    protected function setUp(): void
    {
        parent::setUp();

        $this->people = [
            'alexia' => [
                'psn' => '550309-6447',
                'gender' => Gender::Female,
                'dob' => new DateTime('1955-03-09'),
                'age' => $this->calculateAge(new DateTime('1955-03-09')),
            ],
            'otto' => [
                'psn' => '001020-1895',
                'gender' => Gender::Male,
                'dob' => new DateTime('2000-10-20'),
                'age' => $this->calculateAge(new DateTime('2000-10-20')),
            ],
            'lowa' => [
                'psn' => '751208-0222',
                'gender' => Gender::Female,
                'dob' => new DateTime('1975-12-08'),
                'age' => $this->calculateAge(new DateTime('1975-12-08')),
            ],
            'edward' => [
                'psn' => '19771211-2775',
                'gender' => Gender::Male,
                'dob' => new DateTime('1977-12-11'),
                'age' => $this->calculateAge(new DateTime('1977-12-11')),
            ],
            'maia' => [
                'psn' => '380519-7807',
                'gender' => Gender::Female,
                'dob' => new DateTime('1938-05-19'),
                'age' => $this->calculateAge(new DateTime('1938-05-19')),
            ],
        ];

        $this->invalidIds = [
            '387519-7007',
            '710233-2617',
            '554309-6447',
            '964704-4519',
            '750008-0222',
        ];
    }

    public function test_extract_behaviour(): void
    {
        foreach ($this->people as $person) {
            $citizen = $this->socrates->getCitizenDataFromId($person['psn'], Country::Sweden);

            self::assertEquals($person['gender'], $citizen->getGender());
            self::assertEquals($person['dob'], $citizen->getDateOfBirth());
            self::assertEquals($person['age'], $citizen->getAge());
        }

        $this->expectException(InvalidLengthException::class);

        $this->socrates->getCitizenDataFromId('324432-343', Country::Sweden);
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->people as $person) {
            self::assertTrue(
                $this->socrates->validateId($person['psn'], Country::Sweden)
            );
        }

        foreach ($this->invalidIds as $invalidId) {
            self::assertFalse(
                $this->socrates->validateId($invalidId, Country::Sweden)
            );
        }

        $this->expectException(InvalidLengthException::class);

        $this->socrates->validateId('21442-2411', Country::Sweden);
    }
}
