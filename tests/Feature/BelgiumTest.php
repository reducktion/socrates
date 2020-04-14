<?php

namespace Reducktion\Socrates\Tests\Feature;

use Carbon\Carbon;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Facades\Socrates;

class BelgiumTest extends FeatureTest
{
    private $people;

    private $invalidIds;

    public function setUp(): void
    {
        parent::setUp();

        $this->people = [
            'shahin' => [
                'id' => '93.05.18-223.61',
                'gender' => Gender::MALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '1993-05-18'),
                'age' => Carbon::createFromFormat('Y-m-d', '1993-05-18')->age,
            ],
            'naoual' => [
                'id' => '730111-361-73',
                'gender' => Gender::MALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '1973-01-11'),
                'age' => Carbon::createFromFormat('Y-m-d', '1973-01-11')->age,
            ],
            'xavi' => [
                'id' => '75.12.05-137.14',
                'gender' => Gender::MALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '1975-12-05'),
                'age' => Carbon::createFromFormat('Y-m-d', '1975-12-05')->age,
            ],
            'ute' => [
                'id' => '09.08.24-282.48',
                'gender' => Gender::FEMALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '2009-08-24'),
                'age' => Carbon::createFromFormat('Y-m-d', '2009-08-24')->age,
            ],
            'kurt' => [
                'id' => '71.09.07-213.64',
                'gender' => Gender::MALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '1971-09-07'),
                'age' => Carbon::createFromFormat('Y-m-d', '1971-09-07')->age,
            ],
        ];

        $this->invalidIds = [
            '12.12.12-132.32',
            '97.12.03-123.12',
            '01.06.18-468.99',
            '64.04.09-874.43',
            '12.10.23-954.11'
        ];
    }

    public function test_extract_behaviour(): void
    {
        foreach ($this->people as $person) {
            $citizen = Socrates::getCitizenDataFromId($person['id'], 'BE');
            $this->assertEquals($person['gender'], $citizen->getGender());
            $this->assertEquals($person['dob'], $citizen->getDateOfBirth());
            $this->assertEquals($person['age'], $citizen->getAge());
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::getCitizenDataFromId('12.12.12-1323.32', 'BE');
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->people as $person) {
            $this->assertTrue(
                Socrates::validateId($person['id'], 'BE')
            );
        }

        foreach ($this->invalidIds as $id) {
            $this->assertFalse(
                Socrates::validateId($id, 'BE')
            );
        }
    }
}