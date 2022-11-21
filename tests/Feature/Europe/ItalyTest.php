<?php

namespace Reducktion\Socrates\Tests\Feature\Europe;

use DateTime;
use Reducktion\Socrates\Constants\Country;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Exceptions\InvalidIdException;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Tests\Feature\FeatureTest;

class ItalyTest extends FeatureTest
{
    private array $people;
    private array $invalidIds;

    protected function setUp(): void
    {
        parent::setUp();

        $this->people = [
            'matteo moretti' => [
                'fc' => 'MRTMTT25D09F205Z',
                'gender' => Gender::Male,
                'dob' => new DateTime('1925-04-09'),
                'age' => $this->calculateAge(new DateTime('1925-04-09')),
                'pob' => 'MILANO (MI)'
            ],
            'samantha miller' => [
                'fc' => 'MLLSNT82P65Z404U',
                'gender' => Gender::Female,
                'dob' => new DateTime('1982-09-25'),
                'age' => $this->calculateAge(new DateTime('1982-09-25')),
                'pob' => 'STATI UNITI D\'AMERICA'
            ],
            'delmo castiglione' => [
                'fc' => 'DLMCTG75B07H227Y',
                'gender' => Gender::Male,
                'dob' => new DateTime('1975-02-07'),
                'age' => $this->calculateAge(new DateTime('1975-02-07')),
                'pob' => 'REINO (BN)'
            ],
            'elsa barese' => [
                'fc' => 'BRSLSE08D50H987B',
                'gender' => Gender::Female,
                'dob' => new DateTime('2008-04-10'),
                'age' => $this->calculateAge(new DateTime('2008-04-10')),
                'pob' => 'SAN MARTINO ALFIERI (AT)'
            ],
            'dario marcelo' => [
                'fc' => 'MRCDRA01A13A065E',
                'gender' => Gender::Male,
                'dob' => new DateTime('2001-01-13'),
                'age' => $this->calculateAge(new DateTime('2001-01-13')),
                'pob' => 'AFRICO (RC)'
            ],
            'dario marchesani' => [
                'fc' => 'MRCDRALMAMPALSRE',
                'gender' => Gender::Male,
                'dob' => new DateTime('2001-01-13'),
                'age' => $this->calculateAge(new DateTime('2001-01-13')),
                'pob' => 'AFRICO (RC)'
            ]
        ];

        $this->invalidIds = [
            'MECDRE01A11A025E',
            'ARSLGE02D50H987A',
            'CSTDAM75B06C215T',
            'ARLSNT66P65Z404R',
            'APLSPT96P65Z4051'
        ];
    }

    public function test_extract_behaviour(): void
    {
        foreach ($this->people as $person) {
            $citizen = $this->socrates->getCitizenDataFromId($person['fc'], Country::Italy);

            self::assertEquals($person['gender'], $citizen->getGender());
            self::assertEquals($person['dob'], $citizen->getDateOfBirth());
            self::assertEquals($person['age'], $citizen->getAge());
            self::assertEquals($person['pob'], $citizen->getPlaceOfBirth());
        }

        $this->expectException(InvalidIdException::class);

        $this->socrates->getCitizenDataFromId('BRSLSE08D50H907B', Country::Italy);
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->people as $person) {
            self::assertTrue(
                $this->socrates->validateId($person['fc'], Country::Italy)
            );
        }

        foreach ($this->invalidIds as $fc) {
            self::assertFalse(
                $this->socrates->validateId($fc, Country::Italy)
            );
        }

        $this->expectException(InvalidLengthException::class);

        $this->socrates->validateId('MLCDTA01A12A06E', Country::Italy);
    }
}
