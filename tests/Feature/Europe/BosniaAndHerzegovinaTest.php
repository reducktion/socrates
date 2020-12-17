<?php

namespace Reducktion\Socrates\Tests\Feature\Europe;

use DateTime;
use Carbon\Carbon;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Laravel\Facades\Socrates;
use Reducktion\Socrates\Tests\Feature\FeatureTest;

class BosniaAndHerzegovinaTest extends FeatureTest
{
    private $people;
    private $invalidIds;

    protected function setUp(): void
    {
        parent::setUp();

        $this->people = [
            'Naser' => [
                'jmbg' => '1502957172694',
                'gender' => Gender::MALE,
                'dob' => new DateTime('1957-02-15'),
                'age' => $this->calculateAge(new DateTime('1957-02-15')),
                'pob' => 'Sarajevo - Bosnia and Herzegovina'
            ],
            'Imran' => [
                'jmbg' => '2508995191483',
                'gender' => Gender::MALE,
                'dob' => new DateTime('1995-08-25'),
                'age' => $this->calculateAge(new DateTime('1995-08-25')),
                'pob' => 'Zenica - Bosnia and Herzegovina'
            ],
            'Ajdin' => [
                'jmbg' => '1012980163603',
                'gender' => Gender::MALE,
                'dob' => new DateTime('1980-12-10'),
                'age' => $this->calculateAge(new DateTime('1980-12-10')),
                'pob' => 'Prijedor - Bosnia and Herzegovina'
            ],
            'Merjem' => [
                'jmbg' => '1310963145538',
                'gender' => Gender::FEMALE,
                'dob' => new DateTime('1963-10-13'),
                'age' => $this->calculateAge(new DateTime('1963-10-13')),
                'pob' => 'Livno - Bosnia and Herzegovina'
            ],
            'Eman' => [
                'jmbg' => '1806998154160',
                'gender' => Gender::MALE,
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
            $citizen = Socrates::getCitizenDataFromId($person['jmbg'], 'BA');

            self::assertEquals($person['gender'], $citizen->getGender());
            self::assertEquals(Carbon::instance($person['dob']), $citizen->getDateOfBirth());
            self::assertEquals($person['dob'], $citizen->getDateOfBirthNative());
            self::assertEquals($person['age'], $citizen->getAge());
            self::assertEquals($person['pob'], $citizen->getPlaceOfBirth());
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::getCitizenDataFromId('010597850041', 'BA');
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->people as $person) {
            self::assertTrue(
                Socrates::validateId($person['jmbg'], 'BA')
            );
        }

        foreach ($this->invalidIds as $jmbg) {
            self::assertFalse(
                Socrates::validateId($jmbg, 'BA')
            );
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::validateId('1012999121', 'BA');
    }
}
