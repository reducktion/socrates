<?php

namespace Reducktion\Socrates\Tests\Feature\Europe;

use Carbon\Carbon;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Laravel\Facades\Socrates;
use Reducktion\Socrates\Tests\Feature\FeatureTest;

class FranceTest extends FeatureTest
{
    private $people;
    private $invalidIds;

    protected function setUp(): void
    {
        parent::setUp();

        $this->people = [
            'Annette' => [
                'insee' => '2820819398814 09',
                'gender' => Gender::FEMALE,
                'dob' => Carbon::createFromFormat('Y-m', '1982-08'),
                'age' => Carbon::createFromFormat('Y-m', '1982-08')->age,
                'pob' => 'Corrèze'
            ],
            'Lance' => [
                'insee' => '1350455179061 16',
                'gender' => Gender::MALE,
                'dob' => Carbon::createFromFormat('Y-m', '1935-04'),
                'age' => Carbon::createFromFormat('Y-m', '1935-04')->age,
                'pob' => 'Meuse'
            ],
            'Ancelote' => [
                'insee' => '2381080214568 11',
                'gender' => Gender::FEMALE,
                'dob' => Carbon::createFromFormat('Y-m', '1938-10'),
                'age' => Carbon::createFromFormat('Y-m', '1938-10')->age,
                'pob' => 'Somme'
            ],
            'Lothair' => [
                'insee' => '1880858704571 57',
                'gender' => Gender::MALE,
                'dob' => Carbon::createFromFormat('Y-m', '1988-08'),
                'age' => Carbon::createFromFormat('Y-m', '1988-08')->age,
                'pob' => 'Nièvre'
            ],
            'Millard' => [
                'insee' => '1030307795669 72',
                'gender' => Gender::MALE,
                'dob' => Carbon::createFromFormat('Y-m', '2003-03'),
                'age' => Carbon::createFromFormat('Y-m', '2003-03')->age,
                'pob' => 'Ardèche'
            ],
            'Geoffrey' => [
                'insee' => '1820897401154 75',
                'gender' => Gender::MALE,
                'dob' => Carbon::createFromFormat('Y-m', '1982-08'),
                'age' => Carbon::createFromFormat('Y-m', '1982-08')->age,
                'pob' => 'Réunion'
            ],
            'Galatee' => [
                'insee' => '2041098718061 61',
                'gender' => Gender::FEMALE,
                'dob' => Carbon::createFromFormat('Y-m', '2004-10'),
                'age' => Carbon::createFromFormat('Y-m', '2004-10')->age,
                'pob' => 'French Polynesia'
            ],
            'Leal' => [
                'insee' => '1103442505781 11',
                'gender' => Gender::MALE,
                'dob' => Carbon::createFromFormat('Y-m', '2010-04'),
                'age' => Carbon::createFromFormat('Y-m', '2010-04')->age,
                'pob' => 'Loire'
            ],
            'Odelette' => [
                'insee' => '2115028242370 20',
                'gender' => Gender::FEMALE,
                'dob' => Carbon::createFromFormat('Y', '2011'),
                'age' => Carbon::createFromFormat('Y', '2011')->age,
                'pob' => 'Eure-et-Loir'
            ],
            'Roch' => [
                'insee' => '199072A228070 10',
                'gender' => Gender::MALE,
                'dob' => Carbon::createFromFormat('Y-m', '1999-07'),
                'age' => Carbon::createFromFormat('Y-m', '1999-07')->age,
                'pob' => 'Corse-du-Sud'
            ],
            'Nadine' => [
                'insee' => '257092B844458 87',
                'gender' => Gender::FEMALE,
                'dob' => Carbon::createFromFormat('Y-m', '1957-09'),
                'age' => Carbon::createFromFormat('Y-m', '1957-09')->age,
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
            $citizen = Socrates::getCitizenDataFromId($person['insee'], 'FR');
            $this->assertEquals($person['gender'], $citizen->getGender());
            $this->assertEquals($person['dob'], $citizen->getDateOfBirth());
            $this->assertEquals($person['age'], $citizen->getAge());
            $this->assertEquals($person['pob'], $citizen->getPlaceOfBirth());
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::getCitizenDataFromId('10316221921 22', 'FR');
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->people as $person) {
            $this->assertTrue(
                Socrates::validateId($person['insee'], 'FR')
            );
        }

        foreach ($this->invalidIds as $insee) {
            $this->assertFalse(
                Socrates::validateId($insee, 'FR')
            );
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::validateId('23127609898 01', 'FR');
    }
}
