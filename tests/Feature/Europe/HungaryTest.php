<?php

namespace Reducktion\Socrates\Tests\Feature\Europe;

use DateTime;
use PHPUnit\Framework\Attributes\DataProvider;
use Reducktion\Socrates\Constants\Country;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Tests\Feature\FeatureTestCase;

class HungaryTest extends FeatureTestCase
{
    public static function peopleDataProvider(): array
    {
        return [
            'Aliz' => [
                'person' => [
                    'pin' => '2-720216-1673',
                    'gender' => Gender::Female,
                    'dob' => new DateTime('1972-02-16'),
                    'age' => self::calculateAge(new DateTime('1972-02-16')),
                ],
            ],
            'Dora' => [
                'person' => [
                    'pin' => '2-690609-5528',
                    'gender' => Gender::Female,
                    'dob' => new DateTime('1969-06-09'),
                    'age' => self::calculateAge(new DateTime('1969-06-09')),
                ],
            ],
            'Jolan' => [
                'person' => [
                    'pin' => '2-840320-0414',
                    'gender' => Gender::Female,
                    'dob' => new DateTime('1984-03-20'),
                    'age' => self::calculateAge(new DateTime('1984-03-20')),
                ],
            ],
            'Kapolcs' => [
                'person' => [
                    'pin' => '3-101010-5646',
                    'gender' => Gender::Male,
                    'dob' => new DateTime('2010-10-10'),
                    'age' => self::calculateAge(new DateTime('2010-10-10')),
                ],
            ],
            'Vincze' => [
                'person' => [
                    'pin' => '3-080321-8523',
                    'gender' => Gender::Male,
                    'dob' => new DateTime('2008-03-21'),
                    'age' => self::calculateAge(new DateTime('2008-03-21')),
                ],
            ],
        ];
    }

    public static function invalidIdsDataProvider(): array
    {
        return [];
    }

    #[DataProvider('peopleDataProvider')]
    public function test_extract_behaviour(array $person): void
    {
        $citizen = $this->socrates->getCitizenDataFromId($person['pin'], Country::Hungary);
        self::assertEquals($person['gender'], $citizen->getGender());
        self::assertEquals($person['dob'], $citizen->getDateOfBirth());
        self::assertEquals($person['age'], $citizen->getAge());
    }

    #[DataProvider('peopleDataProvider')]
    public function test_validation_with_valid_ids_passes(array $person): void
    {
        self::assertTrue($this->socrates->validateId($person['pin'], Country::Hungary));
    }

    #[DataProvider('invalidIdsDataProvider')]
    public function test_validation_with_invalid_ids_fails(string $invalidId): void
    {
        self::assertFalse($this->socrates->validateId($invalidId, Country::Hungary));
    }

    public function test_validation_using_ids_with_invalid_length_throw_exception(): void
    {
        $this->expectException(InvalidLengthException::class);

        $this->socrates->validateId('26905528', Country::Hungary);
    }
}
