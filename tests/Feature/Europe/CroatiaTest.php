<?php

namespace Reducktion\Socrates\Tests\Feature\Europe;

use DateTime;
use Reducktion\Socrates\Constants\Country;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Tests\Feature\FeatureTest;

class CroatiaTest extends FeatureTest
{
    private array $people;
    private array $validIds;
    private array $invalidIds;

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
                'gender' => Gender::Female,
                'dob' => new DateTime('1988-09-18'),
                'age' => $this->calculateAge(new DateTime('1988-09-18')),
                'pob' => 'Osijek, Slavonia region - Croatia'
            ],
            'Ana' => [
                'jmbg' => '0808928315425',
                'gender' => Gender::Female,
                'dob' => new DateTime('1928-08-08'),
                'age' => $this->calculateAge(new DateTime('1928-08-08')),
                'pob' => 'Bjelovar, Virovitica, Koprivnica, Pakrac, Podravina region - Croatia'
            ],
            'Marija' => [
                'jmbg' => '1106961359224',
                'gender' => Gender::Female,
                'dob' => new DateTime('1961-06-11'),
                'age' => $this->calculateAge(new DateTime('1961-06-11')),
                'pob' => 'Gospić, Lika region - Croatia'
            ],
            'Stjepan' => [
                'jmbg' => '1105951323209',
                'gender' => Gender::Male,
                'dob' => new DateTime('1951-05-11'),
                'age' => $this->calculateAge(new DateTime('1951-05-11')),
                'pob' => 'Varaždin, Međimurje region - Croatia'
            ],
            'Ivan' => [
                'jmbg' => '2109971352638',
                'gender' => Gender::Male,
                'dob' => new DateTime('1971-09-21'),
                'age' => $this->calculateAge(new DateTime('1971-09-21')),
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
            $citizen = $this->socrates->getCitizenDataFromId($person['jmbg'], Country::Croatia);

            self::assertEquals($person['gender'], $citizen->getGender());
            self::assertEquals($person['dob'], $citizen->getDateOfBirth());
            self::assertEquals($person['age'], $citizen->getAge());
            self::assertEquals($person['pob'], $citizen->getPlaceOfBirth());
        }

        $this->expectException(InvalidLengthException::class);

        foreach ($this->validIds as $person) {
            $this->socrates->getCitizenDataFromId($person, Country::Croatia);
        }

        $this->socrates->getCitizenDataFromId('1821992971', Country::Croatia);
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->validIds as $oib) {
            self::assertTrue(
                $this->socrates->validateId($oib, Country::Croatia)
            );
        }

        foreach ($this->people as $person) {
            self::assertTrue(
                $this->socrates->validateId($person['jmbg'], Country::Croatia)
            );
        }

        foreach ($this->invalidIds as $jmbg) {
            self::assertFalse(
                $this->socrates->validateId($jmbg, Country::Croatia)
            );
        }

        $this->expectException(InvalidLengthException::class);

        $this->socrates->validateId('010597850', Country::Croatia);
    }
}
