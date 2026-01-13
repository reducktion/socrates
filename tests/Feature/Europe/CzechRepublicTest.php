<?php

namespace Reducktion\Socrates\Tests\Feature\Europe;

use DateTime;
use PHPUnit\Framework\Attributes\DataProvider;
use Reducktion\Socrates\Constants\Country;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Tests\Feature\FeatureTestCase;

class CzechRepublicTest extends FeatureTestCase
{
    public static function peopleDataProvider(): array
    {
        return [
            'Michal' => [
                'person' => [
                    'rc' => '990224/9258',
                    'gender' => Gender::Male,
                    'dob' => new DateTime('1999-02-24'),
                    'age' => self::calculateAge(new DateTime('1999-02-24')),
                ],
            ],
            'Tereza' => [
                'person' => [
                    'rc' => '0157155328',
                    'gender' => Gender::Female,
                    'dob' => new DateTime('2001-07-15'),
                    'age' => self::calculateAge(new DateTime('2001-07-15')),
                ],
            ],
            'Adéla' => [
                'person' => [
                    'rc' => '975406/2494',
                    'gender' => Gender::Female,
                    'dob' => new DateTime('1997-04-06'),
                    'age' => self::calculateAge(new DateTime('1997-04-06')),
                ],
            ],
            'Lucie' => [
                'person' => [
                    'rc' => '956022/6027',
                    'gender' => Gender::Female,
                    'dob' => new DateTime('1995-10-22'),
                    'age' => self::calculateAge(new DateTime('1995-10-22')),
                ],
            ],
            'Petr' => [
                'person' => [
                    'rc' => '960326/2955',
                    'gender' => Gender::Male,
                    'dob' => new DateTime('1996-03-26'),
                    'age' => self::calculateAge(new DateTime('1996-03-26')),
                ],
            ],
        ];
    }

    public static function invalidIdsDataProvider(): array
    {
        return [
            ['010819/7762'],
            ['715108/0998'],
            ['960326/1297'],
            ['995311/1928'],
            ['886026/8751'],
        ];
    }

    #[DataProvider('peopleDataProvider')]
    public function test_extract_behaviour(array $person): void
    {
        $citizen = $this->socrates->getCitizenDataFromId($person['rc'], Country::CzechRepublic);
        self::assertEquals($person['gender'], $citizen->getGender());
        self::assertEquals($person['dob'], $citizen->getDateOfBirth());
        self::assertEquals($person['age'], $citizen->getAge());
    }

    #[DataProvider('peopleDataProvider')]
    public function test_validation_with_valid_ids_passes(array $person): void
    {
        self::assertTrue($this->socrates->validateId($person['rc'], Country::CzechRepublic));
    }

    #[DataProvider('invalidIdsDataProvider')]
    public function test_validation_with_invalid_ids_fails(string $invalidId): void
    {
        self::assertFalse($this->socrates->validateId($invalidId, Country::CzechRepublic));
    }

    public function test_validation_using_ids_with_invalid_length_throw_exception(): void
    {
        $this->expectException(InvalidLengthException::class);

        $this->socrates->validateId('88606/875', Country::CzechRepublic);
    }
}
