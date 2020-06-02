<?php

namespace Reducktion\Socrates\Tests\Feature;

use Carbon\Carbon;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Laravel\Facades\Socrates;

class FinlandTest extends FeatureTest
{
    private $people;
    private $invalidIds;

    protected function setUp(): void
    {
        parent::setUp();

        $this->people = [
            'senja' => [
                'hetu' => '040560-600E',
                'gender' => Gender::FEMALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '1960-05-04'),
                'age' => Carbon::createFromFormat('Y-m-d', '1960-05-04')->age
            ],
            'elias' => [
                'hetu' => '121093-275N',
                'gender' => Gender::MALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '1993-10-12'),
                'age' => Carbon::createFromFormat('Y-m-d', '1993-10-12')->age
            ],
            'ida' => [
                'hetu' => '260555-512H',
                'gender' => Gender::FEMALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '1955-05-26'),
                'age' => Carbon::createFromFormat('Y-m-d', '1955-05-26')->age
            ],
            'iiro' => [
                'hetu' => '110416A479W',
                'gender' => Gender::MALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '2016-04-11'),
                'age' => Carbon::createFromFormat('Y-m-d', '2016-04-11')->age
            ],
            'stig' => [
                'hetu' => '040403A2676',
                'gender' => Gender::MALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '2003-04-04'),
                'age' => Carbon::createFromFormat('Y-m-d', '2003-04-04')->age
            ]
        ];

        $this->invalidIds = [
            '050403+2676',
            '110414-479W',
            '653416A549B',
            '122417-456T',
            '121212A479F'
        ];
    }

    public function test_extract_behaviour(): void
    {
        foreach ($this->people as $person) {
            $citizen = Socrates::getCitizenDataFromId($person['hetu'], 'FI');

            $this->assertEquals($person['gender'], $citizen->getGender());
            $this->assertEquals($person['dob'], $citizen->getDateOfBirth());
            $this->assertEquals($person['age'], $citizen->getAge());
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::getCitizenDataFromId('04403A2676', 'FI');
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->people as $person) {
            $this->assertTrue(
                Socrates::validateId($person['hetu'], 'FI')
            );
        }

        foreach ($this->invalidIds as $hetu) {
            $this->assertFalse(
                Socrates::validateId($hetu, 'FI')
            );
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::validateId('21344-6451', 'FI');
    }
}
