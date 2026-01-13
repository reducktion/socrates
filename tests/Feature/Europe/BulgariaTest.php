<?php

namespace Reducktion\Socrates\Tests\Feature\Europe;

use DateTime;
use PHPUnit\Framework\Attributes\DataProvider;
use Reducktion\Socrates\Constants\Country;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Tests\Feature\FeatureTestCase;

class BulgariaTest extends FeatureTestCase
{
    public static function peopleDataProvider(): array
    {
        return [
            'Andrei' => [
                'person' => [
                    'egn' => '7523169263',
                    'gender' => Gender::Male,
                    'dob' => new DateTime('1875-03-16'),
                    'age' => self::calculateAge(new DateTime('1875-03-16')),
                ],
            ],
            'Lyuben' => [
                'person' => [
                    'egn' => '8032056031',
                    'gender' => Gender::Male,
                    'dob' => new DateTime('1880-12-05'),
                    'age' => self::calculateAge(new DateTime('1880-12-05')),
                ],
            ],
            'Bilyana' => [
                'person' => [
                    'egn' => '8001010008',
                    'gender' => Gender::Female,
                    'dob' => new DateTime('1980-01-01'),
                    'age' => self::calculateAge(new DateTime('1980-01-01')),
                ],
            ],
            'Kalina' => [
                'person' => [
                    'egn' => '7501020018',
                    'gender' => Gender::Female,
                    'dob' => new DateTime('1975-01-02'),
                    'age' => self::calculateAge(new DateTime('1975-01-02')),
                ],
            ],
            'Nedyalko' => [
                'person' => [
                    'egn' => '7552010005',
                    'gender' => Gender::Male,
                    'dob' => new DateTime('2075-12-01'),
                    'age' => self::calculateAge(new DateTime('2075-12-01')),
                ],
            ],
            'Tsveta' => [
                'person' => [
                    'egn' => '7542011030',
                    'gender' => Gender::Female,
                    'dob' => new DateTime('2075-02-01'),
                    'age' => self::calculateAge(new DateTime('2075-02-01')),
                ],
            ]
        ];
    }

    public static function invalidIdsDataProvider(): array
    {
        return [
            ['7542021030'],
            ['8002560008'],
            ['3542027033'],
            ['6002567498'],
            ['7542039611'],
        ];
    }

    #[DataProvider('peopleDataProvider')]
    public function test_extract_behaviour(array $person): void
    {
        $citizen = $this->socrates->getCitizenDataFromId($person['egn'], Country::Bulgaria);
        self::assertEquals($person['gender'], $citizen->getGender());
        self::assertEquals($person['dob'], $citizen->getDateOfBirth());
        self::assertEquals($person['age'], $citizen->getAge());
    }

    #[DataProvider('peopleDataProvider')]
    public function test_validation_with_valid_ids_passes(array $person): void
    {
        self::assertTrue($this->socrates->validateId($person['egn'], Country::Bulgaria));
    }

    #[DataProvider('invalidIdsDataProvider')]
    public function test_validation_with_invalid_ids_fails(string $invalidId): void
    {
        self::assertFalse($this->socrates->validateId($invalidId, Country::Bulgaria));
    }

    public function test_validation_using_ids_with_invalid_length_throw_exception(): void
    {
        $this->expectException(InvalidLengthException::class);

        $this->socrates->validateId('754201103', Country::Bulgaria);
    }
}
