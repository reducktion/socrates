<?php

namespace Reducktion\Socrates\Tests\Feature\Europe;

use DateTime;
use PHPUnit\Framework\Attributes\DataProvider;
use Reducktion\Socrates\Constants\Country;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Tests\Feature\FeatureTestCase;

class BosniaAndHerzegovinaTest extends FeatureTestCase
{
    public static function peopleDataProvider(): array
    {
        return [
            'Naser' => [
                'person' => [
                    'jmbg' => '1502957172694',
                    'gender' => Gender::Male,
                    'dob' => new DateTime('1957-02-15'),
                    'age' => self::calculateAge(new DateTime('1957-02-15')),
                    'pob' => 'Sarajevo - Bosnia and Herzegovina'
                ],
            ],
            'Imran' => [
                'person' => [
                    'jmbg' => '2508995191483',
                    'gender' => Gender::Male,
                    'dob' => new DateTime('1995-08-25'),
                    'age' => self::calculateAge(new DateTime('1995-08-25')),
                    'pob' => 'Zenica - Bosnia and Herzegovina'
                ],
            ],
            'Ajdin' => [
                'person' => [
                    'jmbg' => '1012980163603',
                    'gender' => Gender::Male,
                    'dob' => new DateTime('1980-12-10'),
                    'age' => self::calculateAge(new DateTime('1980-12-10')),
                    'pob' => 'Prijedor - Bosnia and Herzegovina'
                ],
            ],
            'Merjem' => [
                'person' => [
                    'jmbg' => '1310963145538',
                    'gender' => Gender::Female,
                    'dob' => new DateTime('1963-10-13'),
                    'age' => self::calculateAge(new DateTime('1963-10-13')),
                    'pob' => 'Livno - Bosnia and Herzegovina'
                ],
            ],
            'Eman' => [
                'person' => [
                    'jmbg' => '1806998154160',
                    'gender' => Gender::Male,
                    'dob' => new DateTime('1998-06-18'),
                    'age' => self::calculateAge(new DateTime('1998-06-18')),
                    'pob' => 'Mostar - Bosnia and Herzegovina'
                ],
            ],
        ];
    }

    public static function invalidIdsDataProvider(): array
    {
        return [
            ['1108291065212'],
            ['2808928401264'],
            ['2007950274591'],
            ['2801826817261'],
            ['1012999121239'],
        ];
    }

    #[DataProvider('peopleDataProvider')]
    public function test_extract_behaviour(array $person): void
    {
        $citizen = $this->socrates->getCitizenDataFromId($person['jmbg'], Country::BosniaHerzegovina);
        self::assertEquals($person['gender'], $citizen->getGender());
        self::assertEquals($person['dob'], $citizen->getDateOfBirth());
        self::assertEquals($person['age'], $citizen->getAge());
        self::assertEquals($person['pob'], $citizen->getPlaceOfBirth());
    }

    #[DataProvider('peopleDataProvider')]
    public function test_validation_with_valid_ids_passes(array $person): void
    {
        self::assertTrue($this->socrates->validateId($person['jmbg'], Country::BosniaHerzegovina));
    }

    #[DataProvider('invalidIdsDataProvider')]
    public function test_validation_with_invalid_ids_fails(string $invalidId): void
    {
        self::assertFalse($this->socrates->validateId($invalidId, Country::BosniaHerzegovina));
    }

    public function test_validation_using_ids_with_invalid_length_throw_exception(): void
    {
        $this->expectException(InvalidLengthException::class);

        $this->socrates->validateId('1012999121', Country::BosniaHerzegovina);
    }
}
