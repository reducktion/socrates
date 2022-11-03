<?php

namespace Reducktion\Socrates\Tests\Feature\Europe;

use DateTime;
use Reducktion\Socrates\Constants\Country;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Tests\Feature\FeatureTest;

class KosovoTest extends FeatureTest
{
    private array $people;
    private array $invalidIds;

    protected function setUp(): void
    {
        parent::setUp();

        $this->people = [
            'Fatbard' => [
                'jmbg' => '1009950933098',
                'gender' => Gender::Male,
                'dob' => new DateTime('1950-09-10'),
                'age' => $this->calculateAge(new DateTime('1950-09-10')),
                'pob' => 'Peć region (part of Peć District) - Kosovo'
            ],
            'Erblina' => [
                'jmbg' => '2601966955857',
                'gender' => Gender::Female,
                'dob' => new DateTime('1966-01-26'),
                'age' => $this->calculateAge(new DateTime('1966-01-26')),
                'pob' => 'Prizren region (Prizren District) - Kosovo'
            ],
            'Ajkuna' => [
                'jmbg' => '2202962926257',
                'gender' => Gender::Female,
                'dob' => new DateTime('1962-02-22'),
                'age' => $this->calculateAge(new DateTime('1962-02-22')),
                'pob' => 'Kosovska Mitrovica region (Kosovska Mitrovica District) - Kosovo'
            ],
            'Esad' => [
                'jmbg' => '1404924982109',
                'gender' => Gender::Male,
                'dob' => new DateTime('1924-04-14'),
                'age' => $this->calculateAge(new DateTime('1924-04-14')),
                'pob' => 'Kosovo'
            ],
            'Guzim' => [
                'jmbg' => '2103921983019',
                'gender' => Gender::Male,
                'dob' => new DateTime('1921-03-21'),
                'age' => $this->calculateAge(new DateTime('1921-03-21')),
                'pob' => 'Kosovo'
            ],
        ];

        $this->invalidIds = [];
    }

    public function test_extract_behaviour(): void
    {
        foreach ($this->people as $person) {
            $citizen = $this->socrates->getCitizenDataFromId($person['jmbg'], Country::Kosovo);

            self::assertEquals($person['gender'], $citizen->getGender());
            self::assertEquals($person['dob'], $citizen->getDateOfBirth());
            self::assertEquals($person['age'], $citizen->getAge());
            self::assertEquals($person['pob'], $citizen->getPlaceOfBirth());
        }

        $this->expectException(InvalidLengthException::class);

        $this->socrates->getCitizenDataFromId('010597850041', Country::Kosovo);
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->people as $person) {
            self::assertTrue(
                $this->socrates->validateId($person['jmbg'], Country::Kosovo)
            );
        }

        foreach ($this->invalidIds as $jmbg) {
            self::assertFalse(
                $this->socrates->validateId($jmbg, Country::Kosovo)
            );
        }

        $this->expectException(InvalidLengthException::class);

        $this->socrates->validateId('2103921983', Country::Kosovo);
    }
}
