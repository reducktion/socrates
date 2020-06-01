<?php

namespace Reducktion\Socrates\Tests\Feature;

use Carbon\Carbon;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Laravel\Facades\Socrates;
use Reducktion\Socrates\Exceptions\InvalidLengthException;

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
                'dob' => Carbon::createFromFormat('Y-m-d', '1993-12-13'),
                'age' => Carbon::createFromFormat('Y-m-d', '1993-12-13')->age,
                'pob' => 'Galati'
            ],
            'andrei' => [
                'cnp' => '1941003395747',
                'gender' => Gender::MALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '1994-10-03'),
                'age' => Carbon::createFromFormat('Y-m-d', '1994-10-03')->age,
                'pob' => 'Vrancea'
            ],
            'elena' => [
                'cnp' => '2870917211577',
                'gender' => Gender::FEMALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '1987-09-17'),
                'age' => Carbon::createFromFormat('Y-m-d', '1987-09-17')->age,
                'pob' => 'Ialomita'
            ],
            'florin' => [
                'cnp' => '1850327466200',
                'gender' => Gender::MALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '1985-03-27'),
                'age' => Carbon::createFromFormat('Y-m-d', '1985-03-27')->age,
                'pob' => 'Bucuresti Sectorul 6'
            ],
            'mihai' => [
                'cnp' => '5010318045469',
                'gender' => Gender::MALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '2001-03-18'),
                'age' => Carbon::createFromFormat('Y-m-d', '2001-03-18')->age,
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
            $this->assertEquals($person['gender'], $citizen->getGender());
            $this->assertEquals($person['dob'], $citizen->getDateOfBirth());
            $this->assertEquals($person['age'], $citizen->getAge());
            $this->assertEquals($person['pob'], $citizen->getPlaceOfBirth());
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::getCitizenDataFromId('3050811811', 'RO');
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->people as $person) {
            $this->assertTrue(
                Socrates::validateId($person['cnp'], 'RO')
            );
        }

        foreach ($this->invalidIds as $cnp) {
            $this->assertFalse(
                Socrates::validateId($cnp, 'RO')
            );
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::validateId('30508118', 'RO');
    }
}
