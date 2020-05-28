<?php

namespace Reducktion\Socrates\Tests\Feature;

use Carbon\Carbon;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Laravel\Facades\Socrates;
use Reducktion\Socrates\Exceptions\InvalidLengthException;

class SloveniaTest extends FeatureTest
{
    private $people;
    private $invalidIds;

    protected function setUp(): void
    {
        parent::setUp();

        $this->people = [
            'Luka' => [
                'jmbg' => '0101006500006',
                'gender' => Gender::MALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '2006-01-01'),
                'age' => Carbon::createFromFormat('Y-m-d', '2006-01-01')->age,
                'pob' => 'Slovenia'
            ],
            'Zoja' => [
                'jmbg' => '0310933507830',
                'gender' => Gender::FEMALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '1933-10-03'),
                'age' => Carbon::createFromFormat('Y-m-d', '1933-10-03')->age,
                'pob' => 'Slovenia'
            ],
            'Jaka' => [
                'jmbg' => '1408984500257',
                'gender' => Gender::MALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '1984-08-14'),
                'age' => Carbon::createFromFormat('Y-m-d', '1984-08-14')->age,
                'pob' => 'Slovenia'
            ],
            'Lana' => [
                'jmbg' => '0205962509348',
                'gender' => Gender::FEMALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '1962-05-02'),
                'age' => Carbon::createFromFormat('Y-m-d', '1962-05-02')->age,
                'pob' => 'Slovenia'
            ],
            'Mia' => [
                'jmbg' => '1201962509788',
                'gender' => Gender::FEMALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '1962-01-12'),
                'age' => Carbon::createFromFormat('Y-m-d', '1962-01-12')->age,
                'pob' => 'Slovenia'
            ],
        ];

        $this->invalidIds = [
            '0105978500412',
            '2808928401264',
            '1302953216612',
            '0110951074616',
            '0105182719821',
        ];
    }

    public function test_extract_behaviour(): void
    {
        foreach ($this->people as $person) {
            $citizen = Socrates::getCitizenDataFromId($person['jmbg'], 'SI');

            $this->assertEquals($person['gender'], $citizen->getGender());
            $this->assertEquals($person['dob'], $citizen->getDateOfBirth());
            $this->assertEquals($person['age'], $citizen->getAge());
            $this->assertEquals($person['pob'], $citizen->getPlaceOfBirth());
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::getCitizenDataFromId('010597850041', 'SI');
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->people as $person) {
            $this->assertTrue(
                Socrates::validateId($person['jmbg'], 'SI')
            );
        }

        foreach ($this->invalidIds as $jmbg) {
            $this->assertFalse(
                Socrates::validateId($jmbg, 'SI')
            );
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::getCitizenDataFromId('010597850041', 'SI');
    }

}