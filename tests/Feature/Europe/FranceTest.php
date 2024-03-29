<?php

namespace Reducktion\Socrates\Tests\Feature\Europe;

use DateTime;
use Reducktion\Socrates\Constants\Country;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Tests\Feature\FeatureTest;

class FranceTest extends FeatureTest
{
    private array $people;
    private array $invalidIds;

    protected function setUp(): void
    {
        parent::setUp();

        $this->people = [
            'Annette' => [
                'insee' => '2820819398814 09',
                'gender' => Gender::Female,
                'dob' => DateTime::createFromFormat('Y-m', '1982-08'),
                'age' => $this->calculateAge(DateTime::createFromFormat('Y-m', '1982-08')),
                'pob' => 'Corrèze'
            ],
            'Lance' => [
                'insee' => '1350455179061 16',
                'gender' => Gender::Male,
                'dob' => DateTime::createFromFormat('Y-m', '1935-04'),
                'age' => $this->calculateAge(DateTime::createFromFormat('Y-m', '1935-04')),
                'pob' => 'Meuse'
            ],
            'Ancelote' => [
                'insee' => '2381080214568 11',
                'gender' => Gender::Female,
                'dob' => DateTime::createFromFormat('Y-m', '1938-10'),
                'age' => $this->calculateAge(DateTime::createFromFormat('Y-m', '1938-10')),
                'pob' => 'Somme'
            ],
            'Lothair' => [
                'insee' => '1880858704571 57',
                'gender' => Gender::Male,
                'dob' => DateTime::createFromFormat('Y-m', '1988-08'),
                'age' => $this->calculateAge(DateTime::createFromFormat('Y-m', '1988-08')),
                'pob' => 'Nièvre'
            ],
            'Millard' => [
                'insee' => '1030307795669 72',
                'gender' => Gender::Male,
                'dob' => DateTime::createFromFormat('Y-m', '2003-03'),
                'age' => $this->calculateAge(DateTime::createFromFormat('Y-m', '2003-03')),
                'pob' => 'Ardèche'
            ],
            'Geoffrey' => [
                'insee' => '1820897401154 75',
                'gender' => Gender::Male,
                'dob' => DateTime::createFromFormat('Y-m', '1982-08'),
                'age' => $this->calculateAge(DateTime::createFromFormat('Y-m', '1982-08')),
                'pob' => 'Réunion'
            ],
            'Galatee' => [
                'insee' => '2041098718061 61',
                'gender' => Gender::Female,
                'dob' => DateTime::createFromFormat('Y-m', '2004-10'),
                'age' => $this->calculateAge(DateTime::createFromFormat('Y-m', '2004-10')),
                'pob' => 'French Polynesia'
            ],
            'Leal' => [
                'insee' => '1103442505781 11',
                'gender' => Gender::Male,
                'dob' => DateTime::createFromFormat('Y-m', '2010-04'),
                'age' => $this->calculateAge(DateTime::createFromFormat('Y-m', '2010-04')),
                'pob' => 'Loire'
            ],
            'Odelette' => [
                'insee' => '2115028242370 20',
                'gender' => Gender::Female,
                'dob' => DateTime::createFromFormat('Y', '2011'),
                'age' => $this->calculateAge(DateTime::createFromFormat('Y', '2011')),
                'pob' => 'Eure-et-Loir'
            ],
            'Roch' => [
                'insee' => '199072A228070 10',
                'gender' => Gender::Male,
                'dob' => DateTime::createFromFormat('Y-m', '1999-07'),
                'age' => $this->calculateAge(DateTime::createFromFormat('Y-m', '1999-07')),
                'pob' => 'Corse-du-Sud'
            ],
            'Nadine' => [
                'insee' => '257092B844458 87',
                'gender' => Gender::Female,
                'dob' => DateTime::createFromFormat('Y-m', '1957-09'),
                'age' => $this->calculateAge(DateTime::createFromFormat('Y-m', '1957-09')),
                'pob' => 'Haute-Corse'
            ]
        ];

        $this->invalidIds = [
            '1031629895669 72',
            '2312763214568 54',
            '1031622192811 22',
            '2312763291021 11',
            '2312760989812 01'
        ];
    }

    public function test_extract_behaviour(): void
    {
        foreach ($this->people as $person) {
            $citizen = $this->socrates->getCitizenDataFromId($person['insee'], Country::France);
            self::assertEquals($person['gender'], $citizen->getGender());
            self::assertEquals($person['dob'], $citizen->getDateOfBirth());
            self::assertEquals($person['age'], $citizen->getAge());
            self::assertEquals($person['pob'], $citizen->getPlaceOfBirth());
        }

        $this->expectException(InvalidLengthException::class);

        $this->socrates->getCitizenDataFromId('10316221921 22', Country::France);
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->people as $person) {
            self::assertTrue(
                $this->socrates->validateId($person['insee'], Country::France)
            );
        }

        foreach ($this->invalidIds as $insee) {
            self::assertFalse(
                $this->socrates->validateId($insee, Country::France)
            );
        }

        $this->expectException(InvalidLengthException::class);

        $this->socrates->validateId('23127609898 01', Country::France);
    }
}
