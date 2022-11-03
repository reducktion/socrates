<?php

namespace Reducktion\Socrates\Tests\Feature\Europe;

use DateTime;
use Reducktion\Socrates\Constants\Country;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Tests\Feature\FeatureTest;

class RomaniaTest extends FeatureTest
{
    private array $people;
    private array $invalidIds;

    public function setUp(): void
    {
        parent::setUp();

        $this->people = [
            'alexandra' => [
                'cnp' => '2931213173842',
                'gender' => Gender::Female,
                'dob' => new DateTime('1993-12-13'),
                'age' => $this->calculateAge(new DateTime('1993-12-13')),
                'pob' => 'Galati'
            ],
            'andrei' => [
                'cnp' => '1941003395747',
                'gender' => Gender::Male,
                'dob' => new DateTime('1994-10-03'),
                'age' => $this->calculateAge(new DateTime('1994-10-03')),
                'pob' => 'Vrancea'
            ],
            'elena' => [
                'cnp' => '2870917211577',
                'gender' => Gender::Female,
                'dob' => new DateTime('1987-09-17'),
                'age' => $this->calculateAge(new DateTime('1987-09-17')),
                'pob' => 'Ialomita'
            ],
            'florin' => [
                'cnp' => '1850327466200',
                'gender' => Gender::Male,
                'dob' => new DateTime('1985-03-27'),
                'age' => $this->calculateAge(new DateTime('1985-03-27')),
                'pob' => 'Bucuresti Sectorul 6'
            ],
            'mihai' => [
                'cnp' => '5010318045469',
                'gender' => Gender::Male,
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
            $citizen = $this->socrates->getCitizenDataFromId($person['cnp'], Country::Romania);
            self::assertEquals($person['gender'], $citizen->getGender());
            self::assertEquals($person['dob'], $citizen->getDateOfBirth());
            self::assertEquals($person['age'], $citizen->getAge());
            self::assertEquals($person['pob'], $citizen->getPlaceOfBirth());
        }

        $this->expectException(InvalidLengthException::class);

        $this->socrates->getCitizenDataFromId('3050811811', Country::Romania);
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->people as $person) {
            self::assertTrue(
                $this->socrates->validateId($person['cnp'], Country::Romania)
            );
        }

        foreach ($this->invalidIds as $cnp) {
            self::assertFalse(
                $this->socrates->validateId($cnp, Country::Romania)
            );
        }

        $this->expectException(InvalidLengthException::class);

        $this->socrates->validateId('30508118', Country::Romania);
    }
}
