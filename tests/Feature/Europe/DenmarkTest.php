<?php

namespace Reducktion\Socrates\Tests\Feature\Europe;

use DateTime;
use PHPUnit\Framework\Attributes\DataProvider;
use Reducktion\Socrates\Constants\Country;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Tests\Feature\FeatureTestCase;

class DenmarkTest extends FeatureTestCase
{
    public static function peopleDataProvider(): array
    {
        return [
            'julius' => [
                'person' => [
                    'cpr' => '090792-1395',
                    'gender' => Gender::Male,
                    'dob' => new DateTime('1992-07-09'),
                    'age' => self::calculateAge(new DateTime('1992-07-09')),
                ],
            ],
            'naja' => [
                'person' => [
                    'cpr' => '070593-0600',
                    'gender' => Gender::Female,
                    'dob' => new DateTime('1993-05-07'),
                    'age' => self::calculateAge(new DateTime('1993-05-07')),
                ],
            ],
            'rolla' => [
                'person' => [
                    'cpr' => '150437-3068',
                    'gender' => Gender::Female,
                    'dob' => new DateTime('1937-04-15'),
                    'age' => self::calculateAge(new DateTime('1937-04-15')),
                ],
            ],
            'thomas' => [
                'person' => [
                    'cpr' => '160888-1995',
                    'gender' => Gender::Male,
                    'dob' => new DateTime('1988-08-16'),
                    'age' => self::calculateAge(new DateTime('1988-08-16')),
                ],
            ],
            'mia' => [
                'person' => [
                    'cpr' => '040404-7094',
                    'gender' => Gender::Female,
                    'dob' => new DateTime('2004-04-04'),
                    'age' => self::calculateAge(new DateTime('2004-04-04')),
                ],
            ],
        ];
    }

    public static function invalidIdsDataProvider(): array
    {
        return [
            ['234321-2454'],
            ['333694-0034'],
            ['088383-2313'],
            ['133232-1323'],
            ['040404-7054'],
        ];
    }

    #[DataProvider('peopleDataProvider')]
    public function test_extract_behaviour(array $person): void
    {
        $citizen = $this->socrates->getCitizenDataFromId($person['cpr'], Country::Denmark);
        self::assertEquals($person['gender'], $citizen->getGender());
        self::assertEquals($person['dob'], $citizen->getDateOfBirth());
        self::assertEquals($person['age'], $citizen->getAge());
    }

    #[DataProvider('peopleDataProvider')]
    public function test_validation_with_valid_ids_passes(array $person): void
    {
        self::assertTrue($this->socrates->validateId($person['cpr'], Country::Denmark));
    }

    #[DataProvider('invalidIdsDataProvider')]
    public function test_validation_with_invalid_ids_fails(string $invalidId): void
    {
        self::assertFalse($this->socrates->validateId($invalidId, Country::Denmark));
    }

    public function test_validation_using_ids_with_invalid_length_throw_exception(): void
    {
        $this->expectException(InvalidLengthException::class);

        $this->socrates->validateId('21442-2411', Country::Denmark);
    }
}
