<?php

namespace Reducktion\Socrates\Tests\Feature\Europe;

use Carbon\Carbon;
use DateTime;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Laravel\Facades\Socrates;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Tests\Feature\FeatureTest;

class RomaniaTest extends FeatureTest
{
    private $people;
    private $invalidIds;

    public function setUp(): void
    {
        parent::setUp();

        $this->people = [
            'alexandra' => [
                'cnp' => '2931213173842',
                'gender' => Gender::FEMALE,
                'dob' => new DateTime('1993-12-13'),
                'age' => $this->calculateAge(new DateTime('1993-12-13')),
                'pob' => 'Galati'
            ],
            'andrei' => [
                'cnp' => '1941003395747',
                'gender' => Gender::MALE,
                'dob' => new DateTime('1994-10-03'),
                'age' => $this->calculateAge(new DateTime('1994-10-03')),
                'pob' => 'Vrancea'
            ],
            'elena' => [
                'cnp' => '2870917211577',
                'gender' => Gender::FEMALE,
                'dob' => new DateTime('1987-09-17'),
                'age' => $this->calculateAge(new DateTime('1987-09-17')),
                'pob' => 'Ialomita'
            ],
            'florin' => [
                'cnp' => '1850327466200',
                'gender' => Gender::MALE,
                'dob' => new DateTime('1985-03-27'),
                'age' => $this->calculateAge(new DateTime('1985-03-27')),
                'pob' => 'Bucuresti Sectorul 6'
            ],
            'mihai' => [
                'cnp' => '5010318045469',
                'gender' => Gender::MALE,
                'dob' => new DateTime('2001-03-18'),
                'age' => $this->calculateAge(new DateTime('2001-03-18')),
                'pob' => 'Bacau'
            ]
        ];

        $this->invalidIds = [
            '3840915201134',
            '1050119925624',
            '6060727962137',
            '1060227962273',
            '3174405302101'
        ];
    }

    public function test_extract_behaviour(): void
    {
        foreach ($this->people as $person) {
            $citizen = Socrates::getCitizenDataFromId($person['cnp'], 'RO');
            self::assertEquals($person['gender'], $citizen->getGender());
            self::assertEquals(Carbon::instance($person['dob']), $citizen->getDateOfBirth());
            self::assertEquals($person['dob'], $citizen->getDateOfBirthNative());
            self::assertEquals($person['age'], $citizen->getAge());
            self::assertEquals($person['pob'], $citizen->getPlaceOfBirth());
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::getCitizenDataFromId('3050811811', 'RO');
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->people as $person) {
            self::assertTrue(
                Socrates::validateId($person['cnp'], 'RO')
            );
        }

        foreach ($this->invalidIds as $cnp) {
            self::assertFalse(
                Socrates::validateId($cnp, 'RO')
            );
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::validateId('30508118', 'RO');
    }
}
