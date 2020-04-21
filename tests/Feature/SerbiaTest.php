<?php

namespace Reducktion\Socrates\Tests\Feature;

use Carbon\Carbon;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Facades\Socrates;

class SerbiaTest extends FeatureTest
{
    private $people;
    private $invalidIds;

    protected function setUp(): void
    {
        parent::setUp();

        $this->people = [
            'Nikola' => [
                'jmbg' => '0101100710006',
                'gender' => Gender::MALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '2100-01-01'),
                'age' => Carbon::createFromFormat('Y-m-d', '2100-01-01')->age,
                'pob' => 'Belgrade region (City of Belgrade) - Central Serbia'
            ],
            'Miloje' => [
                'jmbg' => '0110951074616',
                'gender' => Gender::MALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '1951-10-01'),
                'age' => Carbon::createFromFormat('Y-m-d', '1951-10-01')->age,
                'pob' => 'foreigners in Serbian province of Vojvodina'
            ],
            'Teodora' => [
                'jmbg' => '2702937737434',
                'gender' => Gender::FEMALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '1937-02-27'),
                'age' => Carbon::createFromFormat('Y-m-d', '1937-02-27')->age,
                'pob' => 'Niš region (Nišava District, Pirot District and Toplica District) - Central Serbia'
            ],
            'Jana' => [
                'jmbg' => '2606936778324',
                'gender' => Gender::FEMALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '1936-06-26'),
                'age' => Carbon::createFromFormat('Y-m-d', '1936-06-26')->age,
                'pob' => 'Podrinje and Kolubara regions (Mačva District and Kolubara District) - Central Serbia'
            ],
            'Petra' => [
                'jmbg' => '1209992745266',
                'gender' => Gender::FEMALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '1992-09-12'),
                'age' => Carbon::createFromFormat('Y-m-d', '1992-09-12')->age,
                'pob' => 'Southern Morava region (Jablanica District and Pčinja District) - Central Serbia'
            ]
        ];

        $this->invalidIds = [
            '2104108291012',
            '2808928401264',
            '2508995191483',
            '1201962509788',
            '1101987092078'
        ];
    }

    public function test_extract_behaviour(): void
    {
        foreach ($this->people as $person) {
            $citizen = Socrates::getCitizenDataFromId($person['jmbg'], 'RS');

            $this->assertEquals($person['gender'], $citizen->getGender());
            $this->assertEquals($person['dob'], $citizen->getDateOfBirth());
            $this->assertEquals($person['age'], $citizen->getAge());
            $this->assertEquals($person['pob'], $citizen->getPlaceOfBirth());
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::getCitizenDataFromId('010597850041', 'RS');
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->people as $person) {
            $this->assertTrue(
                Socrates::validateId($person['jmbg'], 'RS')
            );
        }

        foreach ($this->invalidIds as $jmbg) {
            $this->assertFalse(
                Socrates::validateId($jmbg, 'RS')
            );
        }
    }

}