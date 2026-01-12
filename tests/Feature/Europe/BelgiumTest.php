<?php

namespace Reducktion\Socrates\Tests\Feature\Europe;

use DateTime;
use PHPUnit\Framework\Attributes\DataProvider;
use Reducktion\Socrates\Constants\Country;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Tests\Feature\FeatureTestCase;

class BelgiumTest extends FeatureTestCase
{
    public static function peopleDataProvider(): array
    {
        return [
            'shahin' => [
                'person' => [
                    'id' => '93.05.18-223.61',
                    'gender' => Gender::Male,
                    'dob' => new DateTime('1993-05-18'),
                    'age' => self::calculateAge(new DateTime('1993-05-18')),
                ],
            ],
            'naoual' => [
                'person' => [
                    'id' => '730111-361-73',
                    'gender' => Gender::Male,
                    'dob' => new DateTime('1973-01-11'),
                    'age' => self::calculateAge(new DateTime('1973-01-11')),
                ],
            ],
            'xavi' => [
                'person' => [
                    'id' => '75.12.05-137.14',
                    'gender' => Gender::Male,
                    'dob' => new DateTime('1975-12-05'),
                    'age' => self::calculateAge(new DateTime('1975-12-05')),
                ],
            ],
            'kurt' => [
                'person' => [
                    'id' => '71.09.07-213.64',
                    'gender' => Gender::Male,
                    'dob' => new DateTime('1971-09-07'),
                    'age' => self::calculateAge(new DateTime('1971-09-07')),
                ],
            ],
            'mark' => [
                'person' => [
                    'id' => '40.00.01-001.33',
                    'gender' => Gender::Male,
                    'dob' => new DateTime('1940-01-01'),
                    'age' => self::calculateAge(new DateTime('1940-01-01')),
                ],
            ],
            // BIS numbers
            'dobAndGenderUnknown1' => [
                'person' => [
                    'id' => '11200274580',
                    'gender' => null,
                    'dob' => null,
                ],
            ],
            'dobAndGenderUnknown2' => [
                'person' => [
                    'id' => '00200203376',
                    'gender' => null,
                    'dob' => null,
                ],
            ],
            'dobUnknownGenderKnown' => [
                'person' => [
                    'id' => '00400048320',
                    'gender' => Gender::Male,
                    'dob' => null,
                ],
            ],
            'dobAndGenderKnown' => [
                'person' => [
                    'id' => '00421090786',
                    'gender' => Gender::Male,
                    'dob' => new DateTime('2000-02-10'),
                    'age' => self::calculateAge(new DateTime('2000-02-10')),
                ],
            ],
        ];
    }

    public static function invalidIdsDataProvider(): array
    {
        return [
            ['12.12.12-132.32'],
            ['97.12.03-123.12'],
            ['01.06.18-468.99'],
            ['64.04.09-874.43'],
            ['12.10.23-954.11'],
            ['00.08.24-282.48'], // invalid age
            ['01.11.16-000.06'], // invalid sequence number
            ['01.11.16-999.74'], // invalid sequence number
        ];
    }

    #[DataProvider('peopleDataProvider')]
    public function test_extract_behaviour(array $person): void
    {
        $citizen = $this->socrates->getCitizenDataFromId($person['id'], Country::Belgium);
        self::assertEquals($person['gender'], $citizen->getGender());
        self::assertEquals($person['dob'], $citizen->getDateOfBirth());
        self::assertEquals($person['age'], $citizen->getAge());

        $this->expectException(InvalidLengthException::class);

        $this->socrates->getCitizenDataFromId('12.12.12-1323.32', Country::Belgium);
    }

    #[DataProvider('peopleDataProvider')]
    public function test_validation_with_valid_ids_passes(array $person): void
    {
        self::assertTrue(
            $this->socrates->validateId($person['id'], Country::Belgium)
        );
    }

    #[DataProvider('invalidIdsDataProvider')]
    public function test_validation_with_invalid_ids_fails(string $invalidId): void
    {
        self::assertFalse(
            $this->socrates->validateId($invalidId, Country::Belgium)
        );
    }

    public function test_validation_using_ids_with_invalid_length_throw_exception(): void
    {
        $this->expectException(InvalidLengthException::class);

        $this->socrates->getCitizenDataFromId('12.12.12-1323.32', Country::Belgium);
    }
}
