<?php

namespace Reducktion\Socrates\Tests\Feature;

use Carbon\Carbon;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Facades\Socrates;

class ItalyTest extends FeatureTest
{
    private $people;
    private $invalidIds;

    protected function setUp(): void
    {
        parent::setUp();

        $this->people = [
            'matteo moretti' => [
                'fc' => 'MRTMTT25D09F205Z',
                'gender' => Gender::MALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '1925-04-09'),
                'age' => Carbon::createFromFormat('Y-m-d', '1925-04-09')->age
            ],
            'samantha miller' => [
                'fc' => 'MLLSNT82P65Z404U',
                'gender' => Gender::FEMALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '1982-09-25'),
                'age' => Carbon::createFromFormat('Y-m-d', '1982-09-25')->age
            ],
            'delmo castiglione' => [
                'fc' => 'CSTDLM75B07C035K',
                'gender' => Gender::MALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '1975-02-07'),
                'age' => Carbon::createFromFormat('Y-m-d', '1975-02-07')->age
            ],
            'elsa barese' => [
                'fc' => 'BRSLSE08D50H987B',
                'gender' => Gender::FEMALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '2008-04-10'),
                'age' => Carbon::createFromFormat('Y-m-d', '2008-04-10')->age
            ],
            'dario marcelo' => [
                'fc' => 'MRCDRA01A13A065E',
                'gender' => Gender::MALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '2001-01-13'),
                'age' => Carbon::createFromFormat('Y-m-d', '2001-01-13')->age
            ],
            'dario marchesani' => [
                'fc' => 'MRCDRALMAMPALSRE',
                'gender' => Gender::MALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '2001-01-13'),
                'age' => Carbon::createFromFormat('Y-m-d', '2001-01-13')->age
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
        $this->assertTrue(true);
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->people as $person) {
            $this->assertTrue(
                Socrates::validateId($person['fc'], 'IT')
            );
        }

        foreach ($this->invalidIds as $fc) {
            $this->assertFalse(
                Socrates::validateId($fc, 'IT')
            );
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::validateId('MLCDTA01A12A06E', 'IT');
    }

}