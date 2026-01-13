<?php

namespace Reducktion\Socrates\Tests\Feature\Europe;

use DateTime;
use PHPUnit\Framework\Attributes\DataProvider;
use Reducktion\Socrates\Constants\Country;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Tests\Feature\FeatureTestCase;

class FinlandTest extends FeatureTestCase
{
    public static function peopleDataProvider(): array
    {
        return [
            'senja' => [
                'person' => [
                    'hetu' => '040560-600E',
                    'gender' => Gender::Female,
                    'dob' => new DateTime('1960-05-04'),
                    'age' => self::calculateAge(new DateTime('1960-05-04')),
                ],
            ],
            'elias' => [
                'person' => [
                    'hetu' => '121093-275N',
                    'gender' => Gender::Male,
                    'dob' => new DateTime('1993-10-12'),
                    'age' => self::calculateAge(new DateTime('1993-10-12')),
                ],
            ],
            'ida' => [
                'person' => [
                    'hetu' => '260555-512H',
                    'gender' => Gender::Female,
                    'dob' => new DateTime('1955-05-26'),
                    'age' => self::calculateAge(new DateTime('1955-05-26')),
                ],
            ],
            'iiro' => [
                'person' => [
                    'hetu' => '110416A479W',
                    'gender' => Gender::Male,
                    'dob' => new DateTime('2016-04-11'),
                    'age' => self::calculateAge(new DateTime('2016-04-11')),
                ],
            ],
            'stig' => [
                'person' => [
                    'hetu' => '040403A2676',
                    'gender' => Gender::Male,
                    'dob' => new DateTime('2003-04-04'),
                    'age' => self::calculateAge(new DateTime('2003-04-04')),
                ],
            ],
        ];
    }

    public static function invalidIdsDataProvider(): array
    {
        return [
            ['050403+2676'],
            ['110414-479W'],
            ['653416A549B'],
            ['122417-456T'],
            ['121212A479F'],
        ];
    }

    #[DataProvider('peopleDataProvider')]
    public function test_extract_behaviour(array $person): void
    {
        $citizen = $this->socrates->getCitizenDataFromId($person['hetu'], Country::Finland);
        self::assertEquals($person['gender'], $citizen->getGender());
        self::assertEquals($person['dob'], $citizen->getDateOfBirth());
        self::assertEquals($person['age'], $citizen->getAge());
    }

    #[DataProvider('peopleDataProvider')]
    public function test_validation_with_valid_ids_passes(array $person): void
    {
        self::assertTrue($this->socrates->validateId($person['hetu'], Country::Finland));
    }

    #[DataProvider('invalidIdsDataProvider')]
    public function test_validation_with_invalid_ids_fails(string $invalidId): void
    {
        self::assertFalse($this->socrates->validateId($invalidId, Country::Finland));
    }

    public function test_validation_using_ids_with_invalid_length_throw_exception(): void
    {
        $this->expectException(InvalidLengthException::class);

        $this->socrates->validateId('21344-6451', Country::Finland);
    }
}
