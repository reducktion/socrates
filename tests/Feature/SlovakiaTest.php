<?php

namespace Reducktion\Socrates\Tests\Feature;

use Carbon\Carbon;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Facades\Socrates;

class SlovakiaTest extends FeatureTest
{
    private $people;
    private $invalidIds;

    protected function setUp(): void
    {
        parent::setUp();

        $this->people = [
            'Boris' => [
                'rc' => '931027/3951',
                'gender' => Gender::MALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '1993-10-27'),
                'age' => Carbon::createFromFormat('Y-m-d', '1993-10-27')->age,
            ],
            'Miroslav' => [
                'rc' => '000816/9733',
                'gender' => Gender::MALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '2000-08-16'),
                'age' => Carbon::createFromFormat('Y-m-d', '2000-08-16')->age,
            ],
            'Natalia' => [
                'rc' => '015612/5552',
                'gender' => Gender::FEMALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '2001-06-12'),
                'age' => Carbon::createFromFormat('Y-m-d', '2001-06-12')->age,
            ],
            'Victoria' => [
                'rc' => '935103/6189',
                'gender' => Gender::FEMALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '1993-01-03'),
                'age' => Carbon::createFromFormat('Y-m-d', '1993-01-03')->age,
            ],
            'Jožko' => [
                'rc' => '010722/4634',
                'gender' => Gender::MALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '2001-07-22'),
                'age' => Carbon::createFromFormat('Y-m-d', '2001-07-22')->age,
            ],
        ];

        $this->invalidIds = [
            '010819/7762',
            '715108/0998',
            '960326/1297',
            '995311/1928',
            '886026/8751',
        ];
    }

    public function test_extract_behaviour(): void
    {
        foreach ($this->people as $person) {
            $citizen = Socrates::getCitizenDataFromId($person['rc'], 'SK');

            $this->assertEquals($person['gender'], $citizen->getGender());
            $this->assertEquals($person['dob'], $citizen->getDateOfBirth());
            $this->assertEquals($person['age'], $citizen->getAge());
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::validateId('88606/875', 'SK');
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->people as $person) {
            $this->assertTrue(
                Socrates::validateId($person['rc'], 'SK')
            );
        }

        foreach ($this->invalidIds as $rc) {
            $this->assertFalse(
                Socrates::validateId($rc, 'SK')
            );
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::validateId('88606/875', 'CZ');
    }

}