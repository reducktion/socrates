<?php

namespace Reducktion\Socrates\Tests\Feature;

use Carbon\Carbon;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Laravel\Facades\Socrates;
use Reducktion\Socrates\Exceptions\InvalidLengthException;

class SwedenTest extends FeatureTest
{
    private $people;
    private $invalidIds;

    protected function setUp(): void
    {
        parent::setUp();

        $this->people = [
            'alexia' => [
                'psn' => '550309-6447',
                'gender' => Gender::FEMALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '1955-03-09'),
                'age' => Carbon::createFromFormat('Y-m-d', '1955-03-09')->age,
            ],
            'otto' => [
                'psn' => '001020-1895',
                'gender' => Gender::MALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '2000-10-20'),
                'age' => Carbon::createFromFormat('Y-m-d', '2000-10-20')->age,
            ],
            'lowa' => [
                'psn' => '751208-0222',
                'gender' => Gender::FEMALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '1975-12-08'),
                'age' => Carbon::createFromFormat('Y-m-d', '1975-12-08')->age,
            ],
            'edward' => [
                'psn' => '19771211-2775',
                'gender' => Gender::MALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '1977-12-11'),
                'age' => Carbon::createFromFormat('Y-m-d', '1977-12-11')->age,
            ],
            'maia' => [
                'psn' => '380519-7807',
                'gender' => Gender::FEMALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '1938-05-19'),
                'age' => Carbon::createFromFormat('Y-m-d', '1938-05-19')->age,
            ],
        ];

        $this->invalidIds = [
            '387519-7007',
            '710233-2617',
            '554309-6447',
            '964704-4519',
            '750008-0222',
        ];
    }

    public function test_extract_behaviour(): void
    {
        foreach ($this->people as $person) {
            $citizen = Socrates::getCitizenDataFromId($person['psn'], 'SE');

            $this->assertEquals($person['gender'], $citizen->getGender());
            $this->assertEquals($person['dob'], $citizen->getDateOfBirth());
            $this->assertEquals($person['age'], $citizen->getAge());
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::getCitizenDataFromId('324432-343', 'SE');
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->people as $person) {
            $this->assertTrue(
                Socrates::validateId($person['psn'], 'SE')
            );
        }

        foreach ($this->invalidIds as $invalidId) {
            $this->assertFalse(
                Socrates::validateId($invalidId, 'SE')
            );
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::validateId('21442-2411', 'SE');
    }
}