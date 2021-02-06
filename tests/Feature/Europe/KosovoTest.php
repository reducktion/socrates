<?php

namespace Reducktion\Socrates\Tests\Feature\Europe;

use Carbon\Carbon;
use DateTime;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Laravel\Facades\Socrates;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Tests\Feature\FeatureTest;

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
                'dob' => new DateTime('1950-09-10'),
                'age' => $this->calculateAge(new DateTime('1950-09-10')),
                'pob' => 'Peć region (part of Peć District) - Kosovo'
            ],
            'Erblina' => [
                'jmbg' => '2601966955857',
                'gender' => Gender::FEMALE,
                'dob' => new DateTime('1966-01-26'),
                'age' => $this->calculateAge(new DateTime('1966-01-26')),
                'pob' => 'Prizren region (Prizren District) - Kosovo'
            ],
            'Ajkuna' => [
                'jmbg' => '2202962926257',
                'gender' => Gender::FEMALE,
                'dob' => new DateTime('1962-02-22'),
                'age' => $this->calculateAge(new DateTime('1962-02-22')),
                'pob' => 'Kosovska Mitrovica region (Kosovska Mitrovica District) - Kosovo'
            ],
            'Esad' => [
                'jmbg' => '1404924982109',
                'gender' => Gender::MALE,
                'dob' => new DateTime('1924-04-14'),
                'age' => $this->calculateAge(new DateTime('1924-04-14')),
                'pob' => 'Kosovo'
            ],
            'Guzim' => [
                'jmbg' => '2103921983019',
                'gender' => Gender::MALE,
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
            $citizen = Socrates::getCitizenDataFromId($person['jmbg'], 'XK');

            self::assertEquals($person['gender'], $citizen->getGender());
            self::assertEquals(Carbon::instance($person['dob']), $citizen->getDateOfBirth());
            self::assertEquals($person['dob'], $citizen->getDateOfBirthNative());
            self::assertEquals($person['age'], $citizen->getAge());
            self::assertEquals($person['pob'], $citizen->getPlaceOfBirth());
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::getCitizenDataFromId('010597850041', 'XK');
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->people as $person) {
            self::assertTrue(
                Socrates::validateId($person['jmbg'], 'XK')
            );
        }

        foreach ($this->invalidIds as $jmbg) {
            self::assertFalse(
                Socrates::validateId($jmbg, 'XK')
            );
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::validateId('2103921983', 'HR');
    }
}
