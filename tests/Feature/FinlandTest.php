<?php

namespace Reducktion\Socrates\Tests\Feature;

use Carbon\Carbon;
use Reducktion\Socrates\Constants\Gender;

class FinlandTest extends FeatureTest
{
    private $people;

    protected function setUp(): void
    {
        parent::setUp();

        $this->people = [
            'senja' => [
                'id' => '040560-600E',
                'gender' => Gender::FEMALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '1960-05-04')
                'age' => Carbon::createFromFormat('Y-m-d', '1960-05-04')->age
            ],
            'elias' => [
                'id' => '121093-275N',
                'gender' => Gender::MALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '1993-10-12')
                'age' => Carbon::createFromFormat('Y-m-d', '1993-10-12')->age
            ],
            'ida' => [
                'id' => '260555-512H',
                'gender' => Gender::FEMALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '1955-05-26')
                'age' => Carbon::createFromFormat('Y-m-d', '1955-05-26')->age
            ],
            'iiro' => [
                'id' => '110416A479W',
                'gender' => Gender::MALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '2016-04-11')
                'age' => Carbon::createFromFormat('Y-m-d', '2016-04-11')->age
            ],
            'stig' => [
                'id' => '040403A2676',
                'gender' => Gender::MALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '2003-04-04')
                'age' => Carbon::createFromFormat('Y-m-d', '2003-04-04')->age
            ]
        ];
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->people as $person) {
            $this->assertTrue(
                Socrates::validateId($person['id'], 'FI')
            );
        }
    }
}