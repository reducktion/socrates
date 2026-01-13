<?php

namespace Reducktion\Socrates\Tests\Feature\Europe;

use DateTime;
use PHPUnit\Framework\Attributes\DataProvider;
use Reducktion\Socrates\Constants\Country;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Tests\Feature\FeatureTestCase;

class FranceTest extends FeatureTestCase
{
    public static function peopleDataProvider(): array
    {
        return [
            'Annette' => [
                'person' => [
                    'insee' => '2820819398814 09',
                    'gender' => Gender::Female,
                    'dob' => DateTime::createFromFormat('Y-m', '1982-08'),
                    'age' => self::calculateAge(DateTime::createFromFormat('Y-m', '1982-08')),
                    'pob' => 'Corrèze',
                ],
            ],
            'Lance' => [
                'person' => [
                    'insee' => '1350455179061 16',
                    'gender' => Gender::Male,
                    'dob' => DateTime::createFromFormat('Y-m', '1935-04'),
                    'age' => self::calculateAge(DateTime::createFromFormat('Y-m', '1935-04')),
                    'pob' => 'Meuse',
                ],
            ],
            'Ancelote' => [
                'person' => [
                    'insee' => '2381080214568 11',
                    'gender' => Gender::Female,
                    'dob' => DateTime::createFromFormat('Y-m', '1938-10'),
                    'age' => self::calculateAge(DateTime::createFromFormat('Y-m', '1938-10')),
                    'pob' => 'Somme',
                ],
            ],
            'Lothair' => [
                'person' => [
                    'insee' => '1880858704571 57',
                    'gender' => Gender::Male,
                    'dob' => DateTime::createFromFormat('Y-m', '1988-08'),
                    'age' => self::calculateAge(DateTime::createFromFormat('Y-m', '1988-08')),
                    'pob' => 'Nièvre',
                ],
            ],
            'Millard' => [
                'person' => [
                    'insee' => '1030307795669 72',
                    'gender' => Gender::Male,
                    'dob' => DateTime::createFromFormat('Y-m', '2003-03'),
                    'age' => self::calculateAge(DateTime::createFromFormat('Y-m', '2003-03')),
                    'pob' => 'Ardèche',
                ],
            ],
            'Geoffrey' => [
                'person' => [
                    'insee' => '1820897401154 75',
                    'gender' => Gender::Male,
                    'dob' => DateTime::createFromFormat('Y-m', '1982-08'),
                    'age' => self::calculateAge(DateTime::createFromFormat('Y-m', '1982-08')),
                    'pob' => 'Réunion',
                ],
            ],
            'Galatee' => [
                'person' => [
                    'insee' => '2041098718061 61',
                    'gender' => Gender::Female,
                    'dob' => DateTime::createFromFormat('Y-m', '2004-10'),
                    'age' => self::calculateAge(DateTime::createFromFormat('Y-m', '2004-10')),
                    'pob' => 'French Polynesia',
                ],
            ],
            'Leal' => [
                'person' => [
                    'insee' => '1103442505781 11',
                    'gender' => Gender::Male,
                    'dob' => DateTime::createFromFormat('Y-m', '2010-04'),
                    'age' => self::calculateAge(DateTime::createFromFormat('Y-m', '2010-04')),
                    'pob' => 'Loire',
                ],
            ],
            'Odelette' => [
                'person' => [
                    'insee' => '2115028242370 20',
                    'gender' => Gender::Female,
                    'dob' => DateTime::createFromFormat('Y', '2011'),
                    'age' => self::calculateAge(DateTime::createFromFormat('Y', '2011')),
                    'pob' => 'Eure-et-Loir',
                ],
            ],
            'Roch' => [
                'person' => [
                    'insee' => '199072A228070 10',
                    'gender' => Gender::Male,
                    'dob' => DateTime::createFromFormat('Y-m', '1999-07'),
                    'age' => self::calculateAge(DateTime::createFromFormat('Y-m', '1999-07')),
                    'pob' => 'Corse-du-Sud',
                ],
            ],
            'Nadine' => [
                'person' => [
                    'insee' => '257092B844458 87',
                    'gender' => Gender::Female,
                    'dob' => DateTime::createFromFormat('Y-m', '1957-09'),
                    'age' => self::calculateAge(DateTime::createFromFormat('Y-m', '1957-09')),
                    'pob' => 'Haute-Corse',
                ],
            ],
        ];
    }

    public static function invalidIdsDataProvider(): array
    {
        return [
            ['1031629895669 72'],
            ['2312763214568 54'],
            ['1031622192811 22'],
            ['2312763291021 11'],
            ['2312760989812 01'],
        ];
    }

    #[DataProvider('peopleDataProvider')]
    public function test_extract_behaviour(array $person): void
    {
        $citizen = $this->socrates->getCitizenDataFromId($person['insee'], Country::France);
        self::assertEquals($person['gender'], $citizen->getGender());
        self::assertEquals($person['dob'], $citizen->getDateOfBirth());
        self::assertEquals($person['age'], $citizen->getAge());
        self::assertEquals($person['pob'], $citizen->getPlaceOfBirth());
    }

    #[DataProvider('peopleDataProvider')]
    public function test_validation_with_valid_ids_passes(array $person): void
    {
        self::assertTrue($this->socrates->validateId($person['insee'], Country::France));
    }

    #[DataProvider('invalidIdsDataProvider')]
    public function test_validation_with_invalid_ids_fails(string $invalidId): void
    {
        self::assertFalse($this->socrates->validateId($invalidId, Country::France));
    }

    public function test_validation_using_ids_with_invalid_length_throw_exception(): void
    {
        $this->expectException(InvalidLengthException::class);

        $this->socrates->validateId('23127609898 01', Country::France);
    }
}
