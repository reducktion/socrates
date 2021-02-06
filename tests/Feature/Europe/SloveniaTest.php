<?php

namespace Reducktion\Socrates\Tests\Feature\Europe;

use Carbon\Carbon;
use DateTime;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Laravel\Facades\Socrates;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Tests\Feature\FeatureTest;

class SloveniaTest extends FeatureTest
{
    private $people;
    private $invalidIds;

    protected function setUp(): void
    {
        parent::setUp();

        $this->people = [
            'Luka' => [
                'emso' => '0101006500006',
                'gender' => Gender::MALE,
                'dob' => new DateTime('2006-01-01'),
                'age' => $this->calculateAge(new DateTime('2006-01-01')),
                'pob' => 'Slovenia',
            ],
            'Zoja' => [
                'emso' => '0310933507830',
                'gender' => Gender::FEMALE,
                'dob' => new DateTime('1933-10-03'),
                'age' => $this->calculateAge(new DateTime('1933-10-03')),
                'pob' => 'Slovenia',
            ],
            'Jaka' => [
                'emso' => '1408984500257',
                'gender' => Gender::MALE,
                'dob' => new DateTime('1984-08-14'),
                'age' => $this->calculateAge(new DateTime('1984-08-14')),
                'pob' => 'Slovenia',
            ],
            'Lana' => [
                'emso' => '0205962509348',
                'gender' => Gender::FEMALE,
                'dob' => new DateTime('1962-05-02'),
                'age' => $this->calculateAge(new DateTime('1962-05-02')),
                'pob' => 'Slovenia',
            ],
            'Mia' => [
                'emso' => '1201962509788',
                'gender' => Gender::FEMALE,
                'dob' => new DateTime('1962-01-12'),
                'age' => $this->calculateAge(new DateTime('1962-01-12')),
                'pob' => 'Slovenia',
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
            $citizen = Socrates::getCitizenDataFromId($person['emso'], 'SI');

            self::assertEquals($person['gender'], $citizen->getGender());
            self::assertEquals(Carbon::instance($person['dob']), $citizen->getDateOfBirth());
            self::assertEquals($person['dob'], $citizen->getDateOfBirthNative());
            self::assertEquals($person['age'], $citizen->getAge());
            self::assertEquals($person['pob'], $citizen->getPlaceOfBirth());
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::getCitizenDataFromId('010597850041', 'SI');
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->people as $person) {
            self::assertTrue(
                Socrates::validateId($person['emso'], 'SI')
            );
        }

        foreach ($this->invalidIds as $emso) {
            self::assertFalse(
                Socrates::validateId($emso, 'SI')
            );
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::getCitizenDataFromId('010597850041', 'SI');
    }
}
