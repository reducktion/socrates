<?php

namespace Reducktion\Socrates\Tests\Feature;

use Carbon\Carbon;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Facades\Socrates;
use Reducktion\Socrates\Exceptions\InvalidLengthException;

class KosovoTest extends FeatureTest
{
    private $people;
    private $invalidIds;

    protected function setUp(): void
    {
        parent::setUp();

        $this->people = [
            'Fatbard' => [
                'jmbg' => '1009950933098',
                'gender' => Gender::MALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '1950-09-10'),
                'age' => Carbon::createFromFormat('Y-m-d', '1950-09-10')->age,
                'pob' => 'Peć region (part of Peć District) - Kosovo'
            ],
            'Erblina' => [
                'jmbg' => '2601966955857',
                'gender' => Gender::FEMALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '1966-01-26'),
                'age' => Carbon::createFromFormat('Y-m-d', '1966-01-26')->age,
                'pob' => 'Prizren region (Prizren District) - Kosovo'
            ],
            'Ajkuna' => [
                'jmbg' => '2202962926257',
                'gender' => Gender::FEMALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '1962-02-22'),
                'age' => Carbon::createFromFormat('Y-m-d', '1962-02-22')->age,
                'pob' => 'Kosovska Mitrovica region (Kosovska Mitrovica District) - Kosovo'
            ],
            'Esad' => [
                'jmbg' => '1404924982109',
                'gender' => Gender::MALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '1924-04-14'),
                'age' => Carbon::createFromFormat('Y-m-d', '1924-04-14')->age,
                'pob' => 'Kosovo'
            ],
            'Guzim' => [
                'jmbg' => '2103921983019',
                'gender' => Gender::MALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '1921-03-21'),
                'age' => Carbon::createFromFormat('Y-m-d', '1921-03-21')->age,
                'pob' => 'Kosovo'
            ],
        ];

        $this->invalidIds = [];
    }

    public function test_extract_behaviour(): void
    {
        foreach ($this->people as $person) {
            $citizen = Socrates::getCitizenDataFromId($person['jmbg'], 'XK');

            $this->assertEquals($person['gender'], $citizen->getGender());
            $this->assertEquals($person['dob'], $citizen->getDateOfBirth());
            $this->assertEquals($person['age'], $citizen->getAge());
            $this->assertEquals($person['pob'], $citizen->getPlaceOfBirth());
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::getCitizenDataFromId('010597850041', 'XK');
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->people as $person) {
            $this->assertTrue(
                Socrates::validateId($person['jmbg'], 'XK')
            );
        }

        foreach ($this->invalidIds as $jmbg) {
            $this->assertFalse(
                Socrates::validateId($jmbg, 'XK')
            );
        }
    }

}