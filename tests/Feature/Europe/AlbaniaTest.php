<?php

namespace Reducktion\Socrates\Tests\Feature\Europe;

use DateTime;
use PHPUnit\Framework\Attributes\DataProvider;
use Reducktion\Socrates\Constants\Country;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Tests\Feature\FeatureTestCase;

class AlbaniaTest extends FeatureTestCase
{
    public static function peopleDataProvider(): array
    {
        return [
            'bardhana' => [
                'person' => [
                    'nid' => 'I05101999I',
                    'gender' => Gender::Female,
                    'dob' => new DateTime('1980-01-01'),
                    'age' => self::calculateAge(new DateTime('1980-01-01')),
                ],
            ],
            'shufti' => [
                'person' => [
                    'nid' => 'I90201535E',
                    'gender' => Gender::Male,
                    'dob' => new DateTime('1989-02-01'),
                    'age' => self::calculateAge(new DateTime('1989-02-01')),
                ],
            ],
            'shyqe' => [
                'person' => [
                    'nid' => 'J45423004V',
                    'gender' => Gender::Female,
                    'dob' => new DateTime('1994-04-23'),
                    'age' => self::calculateAge(new DateTime('1994-04-23')),
                ],
            ],
            'elseid' => [
                'person' => [
                    'nid' => 'H71211672R',
                    'gender' => Gender::Male,
                    'dob' => new DateTime('1977-12-11'),
                    'age' => self::calculateAge(new DateTime('1977-12-11')),
                ],
            ],
            'hasna' => [
                'person' => [
                    'nid' => 'I85413200A',
                    'gender' => Gender::Female,
                    'dob' => new DateTime('1988-04-13'),
                    'age' => self::calculateAge(new DateTime('1988-04-13')),
                ],
            ],
        ];
    }

    public static function invalidIdsDataProvider(): array
    {
        return [
            ['I05101999Q'],
            ['J45423004Y'],
            ['I85413200J'],
            ['I90201535M'],
            ['H71211672A'],
        ];
    }

    #[DataProvider('peopleDataProvider')]
    public function test_extract_behaviour(array $person): void
    {
        $citizen = $this->socrates->getCitizenDataFromId($person['nid'], Country::Albania);
        self::assertEquals($person['gender'], $citizen->getGender());
        self::assertEquals($person['dob'], $citizen->getDateOfBirth());
        self::assertEquals($person['age'], $citizen->getAge());
    }

    #[DataProvider('peopleDataProvider')]
    public function test_validation_with_valid_ids_passes(array $person): void
    {
        self::assertTrue($this->socrates->validateId($person['nid'], Country::Albania));
    }

    #[DataProvider('invalidIdsDataProvider')]
    public function test_validation_with_invalid_ids_fails(string $invalidId): void
    {
        self::assertFalse($this->socrates->validateId($invalidId, Country::Albania));
    }

    public function test_validation_using_ids_with_invalid_length_throw_exception(): void
    {
        $this->expectException(InvalidLengthException::class);

        $this->socrates->validateId('I0785101999I', Country::Albania);
    }
}
