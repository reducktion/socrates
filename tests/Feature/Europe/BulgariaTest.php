<?php

namespace Reducktion\Socrates\Tests\Feature\Europe;

use Carbon\Carbon;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Laravel\Facades\Socrates;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Tests\Feature\FeatureTest;

class BulgariaTest extends FeatureTest
{
    private $people;
    private $invalidIds;

    protected function setUp(): void
    {
        parent::setUp();

        $this->people = [
            'Andrei' => [
                'egn' => '7523169263',
                'gender' => Gender::MALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '1875-03-16'),
                'age' => Carbon::createFromFormat('Y-m-d', '1875-03-16')->age,
            ],
            'Lyuben' => [
                'egn' => '8032056031',
                'gender' => Gender::MALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '1880-12-05'),
                'age' => Carbon::createFromFormat('Y-m-d', '1880-12-05')->age,
            ],
            'Bilyana' => [
                'egn' => '8001010008',
                'gender' => Gender::FEMALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '1980-01-01'),
                'age' => Carbon::createFromFormat('Y-m-d', '1980-01-01')->age,
            ],
            'Kalina' => [
                'egn' => '7501020018',
                'gender' => Gender::FEMALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '1975-01-02'),
                'age' => Carbon::createFromFormat('Y-m-d', '1975-01-02')->age,
            ],
            'Nedyalko' => [
                'egn' => '7552010005',
                'gender' => Gender::MALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '2075-12-01'),
                'age' => Carbon::createFromFormat('Y-m-d', '2075-12-01')->age,
            ],
            'Tsveta' => [
                'egn' => '7542011030',
                'gender' => Gender::FEMALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '2075-02-01'),
                'age' => Carbon::createFromFormat('Y-m-d', '2075-02-01')->age,
            ]
        ];

        $this->invalidIds = [
            '7542021030',
            '8002560008',
            '3542027033',
            '6002567498',
            '7542039611',
        ];
    }

    public function test_extract_behaviour(): void
    {
        foreach ($this->people as $person) {
            $citizen = Socrates::getCitizenDataFromId($person['egn'], 'BG');
            $this->assertEquals($person['gender'], $citizen->getGender());
            $this->assertEquals($person['dob'], $citizen->getDateOfBirth());
            $this->assertEquals($person['age'], $citizen->getAge());
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::getCitizenDataFromId('754201103', 'BG');
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->people as $person) {
            $this->assertTrue(
                Socrates::validateId($person['egn'], 'BG')
            );
        }

        foreach ($this->invalidIds as $egn) {
            $this->assertFalse(
                Socrates::validateId($egn, 'BG')
            );
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::validateId('754201103', 'BG');
    }
}
