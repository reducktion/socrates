<?php

namespace Reducktion\Socrates\Tests\Feature\Europe;

use DateTime;
use Reducktion\Socrates\Constants\Country;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Tests\Feature\FeatureTest;

class BosniaAndHerzegovinaTest extends FeatureTest
{
    private array $people;
    private array $invalidIds;

    protected function setUp(): void
    {
        parent::setUp();

        $this->people = [
            'Naser' => [
                'jmbg' => '1502957172694',
                'gender' => Gender::Male,
                'dob' => new DateTime('1957-02-15'),
                'age' => $this->calculateAge(new DateTime('1957-02-15')),
                'pob' => 'Sarajevo - Bosnia and Herzegovina'
            ],
            'Imran' => [
                'jmbg' => '2508995191483',
                'gender' => Gender::Male,
                'dob' => new DateTime('1995-08-25'),
                'age' => $this->calculateAge(new DateTime('1995-08-25')),
                'pob' => 'Zenica - Bosnia and Herzegovina'
            ],
            'Ajdin' => [
                'jmbg' => '1012980163603',
                'gender' => Gender::Male,
                'dob' => new DateTime('1980-12-10'),
                'age' => $this->calculateAge(new DateTime('1980-12-10')),
                'pob' => 'Prijedor - Bosnia and Herzegovina'
            ],
            'Merjem' => [
                'jmbg' => '1310963145538',
                'gender' => Gender::Female,
                'dob' => new DateTime('1963-10-13'),
                'age' => $this->calculateAge(new DateTime('1963-10-13')),
                'pob' => 'Livno - Bosnia and Herzegovina'
            ],
            'Eman' => [
                'jmbg' => '1806998154160',
                'gender' => Gender::Male,
                'dob' => new DateTime('1998-06-18'),
                'age' => $this->calculateAge(new DateTime('1998-06-18')),
                'pob' => 'Mostar - Bosnia and Herzegovina'
            ]
        ];

        $this->invalidIds = [
            '1108291065212',
            '2808928401264',
            '2007950274591',
            '2801826817261',
            '1012999121239',
        ];
    }

    public function test_extract_behaviour(): void
    {
        foreach ($this->people as $person) {
            $citizen = $this->socrates->getCitizenDataFromId($person['jmbg'], Country::BosniaHerzegovina);

            self::assertEquals($person['gender'], $citizen->getGender());
            self::assertEquals($person['dob'], $citizen->getDateOfBirth());
            self::assertEquals($person['age'], $citizen->getAge());
            self::assertEquals($person['pob'], $citizen->getPlaceOfBirth());
        }

        $this->expectException(InvalidLengthException::class);

        $this->socrates->getCitizenDataFromId('010597850041', Country::BosniaHerzegovina);
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->people as $person) {
            self::assertTrue(
                $this->socrates->validateId($person['jmbg'], Country::BosniaHerzegovina)
            );
        }

        foreach ($this->invalidIds as $jmbg) {
            self::assertFalse(
                $this->socrates->validateId($jmbg, Country::BosniaHerzegovina)
            );
        }

        $this->expectException(InvalidLengthException::class);

        $this->socrates->validateId('1012999121', Country::BosniaHerzegovina);
    }
}
