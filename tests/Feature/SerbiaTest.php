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
            'nikola' => [
                'umcn' => '0101100710006',
                'gender' => Gender::MALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '2100-01-01'),
                'age' => Carbon::createFromFormat('Y-m-d', '2100-01-01')->age,
                'pob' => 'Belgrade region (City of Belgrade) - Central Serbia'
            ]
        ];

        $this->invalidIds = [
            '2104108291012',
        ];
    }

    public function test_extract_behaviour(): void
    {
        foreach ($this->people as $person) {
            $citizen = Socrates::getCitizenDataFromId($person['umcn'], 'RS');

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
                Socrates::validateId($person['umcn'], 'RS')
            );
        }

        foreach ($this->invalidIds as $umcn) {
            $this->assertFalse(
                Socrates::validateId($umcn, 'RS')
            );
        }
    }

}