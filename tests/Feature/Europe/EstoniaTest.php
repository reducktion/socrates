<?php

namespace Reducktion\Socrates\Tests\Feature\Europe;

use DateTime;
use PHPUnit\Framework\Attributes\DataProvider;
use Reducktion\Socrates\Constants\Country;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Tests\Feature\FeatureTestCase;

class EstoniaTest extends FeatureTestCase
{
    public static function peopleDataProvider(): array
    {
        return [
            'Grete' => [
                'person' => [
                    'ik' => '48004119745',
                    'gender' => Gender::Female,
                    'dob' => new DateTime('1980-04-11'),
                    'age' => self::calculateAge(new DateTime('1980-04-11')),
                ],
            ],
            'Kaarel' => [
                'person' => [
                    'ik' => '50108040021',
                    'gender' => Gender::Male,
                    'dob' => new DateTime('2001-08-04'),
                    'age' => self::calculateAge(new DateTime('2001-08-04')),
                ],
            ],
            'Seb' => [
                'person' => [
                    'ik' => '36910180118',
                    'gender' => Gender::Male,
                    'dob' => new DateTime('1969-10-18'),
                    'age' => self::calculateAge(new DateTime('1969-10-18')),
                ],
            ],
            'Jakob' => [
                'person' => [
                    'ik' => '38601230129',
                    'gender' => Gender::Male,
                    'dob' => new DateTime('1986-01-23'),
                    'age' => self::calculateAge(new DateTime('1986-01-23')),
                ],
            ],
            'Katarina' => [
                'person' => [
                    'ik' => '60310275631',
                    'gender' => Gender::Female,
                    'dob' => new DateTime('2003-10-27'),
                    'age' => self::calculateAge(new DateTime('2003-10-27')),
                ],
            ],
        ];
    }

    public static function invalidIdsDataProvider(): array
    {
        return [
            ['88732230129'],
            ['12345630129'],
            ['38608192637'],
            ['10293846198'],
            ['12309132708'],
        ];
    }

    #[DataProvider('peopleDataProvider')]
    public function test_extract_behaviour(array $person): void
    {
        $citizen = $this->socrates->getCitizenDataFromId($person['ik'], Country::Estonia);
        self::assertEquals($person['gender'], $citizen->getGender());
        self::assertEquals($person['dob'], $citizen->getDateOfBirth());
        self::assertEquals($person['age'], $citizen->getAge());
    }

    #[DataProvider('peopleDataProvider')]
    public function test_validation_with_valid_ids_passes(array $person): void
    {
        self::assertTrue($this->socrates->validateId($person['ik'], Country::Estonia));
    }

    #[DataProvider('invalidIdsDataProvider')]
    public function test_validation_with_invalid_ids_fails(string $invalidId): void
    {
        self::assertFalse($this->socrates->validateId($invalidId, Country::Estonia));
    }

    public function test_validation_using_ids_with_invalid_length_throw_exception(): void
    {
        $this->expectException(InvalidLengthException::class);

        $this->socrates->validateId('6031027563', Country::Estonia);
    }
}
