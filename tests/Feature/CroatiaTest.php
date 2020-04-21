<?php

namespace Reducktion\Socrates\Tests\Feature;

use Carbon\Carbon;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Facades\Socrates;
use Reducktion\Socrates\Exceptions\InvalidLengthException;

class CroatiaTest extends FeatureTest
{
    private $validIds;
    private $people;
    private $invalidIds;

    protected function setUp(): void
    {
        parent::setUp();

        $this->validIds = [
            '34562345678',
            '12286373446',
            '97230458182',
            '08214881054',
            '27446063711'
        ];

        $this->people = [
            'Ivana' => [
                'jmbg' => '1809988305313',
                'gender' => Gender::FEMALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '1988-09-18'),
                'age' => Carbon::createFromFormat('Y-m-d', '1988-09-18')->age,
                'pob' => 'Osijek, Slavonia region - Croatia'
            ],
            'Ana' => [
                'jmbg' => '0808928315425',
                'gender' => Gender::FEMALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '1928-08-08'),
                'age' => Carbon::createFromFormat('Y-m-d', '1928-08-08')->age,
                'pob' => 'Bjelovar, Virovitica, Koprivnica, Pakrac, Podravina region - Croatia'
            ],
            'Marija' => [
                'jmbg' => '1106961359224',
                'gender' => Gender::FEMALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '1961-06-11'),
                'age' => Carbon::createFromFormat('Y-m-d', '1961-06-11')->age,
                'pob' => 'Gospić, Lika region - Croatia'
            ],
            'Stjepan' => [
                'jmbg' => '1105951323209',
                'gender' => Gender::MALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '1951-05-11'),
                'age' => Carbon::createFromFormat('Y-m-d', '1951-05-11')->age,
                'pob' => 'Varaždin, Međimurje region - Croatia'
            ],
            'Ivan' => [
                'jmbg' => '2109971352638',
                'gender' => Gender::MALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '1971-09-21'),
                'age' => Carbon::createFromFormat('Y-m-d', '1971-09-21')->age,
                'pob' => 'Gospić, Lika region - Croatia'
            ],
        ];

        $this->invalidIds = [
            '2182791212638',
            '27446182112',
            '27446062711',
            '1181818993013',
            '1821992971638'
        ];
    }

    public function test_extract_behaviour(): void
    {
        foreach ($this->people as $person) {
            $citizen = Socrates::getCitizenDataFromId($person['jmbg'], 'HR');

            $this->assertEquals($person['gender'], $citizen->getGender());
            $this->assertEquals($person['dob'], $citizen->getDateOfBirth());
            $this->assertEquals($person['age'], $citizen->getAge());
            $this->assertEquals($person['pob'], $citizen->getPlaceOfBirth());
        }

        $this->expectException(InvalidLengthException::class);

        foreach ($this->validIds as $person) {
            Socrates::getCitizenDataFromId($person, 'HR');
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::getCitizenDataFromId('1821992971', 'HR');
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->validIds as $id) {
            $this->assertTrue(
                Socrates::validateId($id, 'HR')
            );
        }

        foreach ($this->people as $person) {
            $this->assertTrue(
                Socrates::validateId($person['jmbg'], 'HR')
            );
        }

        foreach ($this->invalidIds as $jmbg) {
            $this->assertFalse(
                Socrates::validateId($jmbg, 'HR')
            );
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::validateId('010597850', 'HR');
    }

}