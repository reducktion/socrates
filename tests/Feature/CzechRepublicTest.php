<?php

namespace Reducktion\Socrates\Tests\Feature;

use Carbon\Carbon;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Facades\Socrates;

class CzechRepublicTest extends FeatureTest
{
    private $people;
    private $invalidIds;

    protected function setUp(): void
    {
        parent::setUp();

        $this->people = [
            '1' => [
                'rc' => '990224/9258',
                'gender' => Gender::MALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '1999-02-24'),
                'age' => Carbon::createFromFormat('Y-m-d', '1999-02-24')->age,
            ],
            '2' => [
                'rc' => '0157155328',
                'gender' => Gender::FEMALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '2001-07-15'),
                'age' => Carbon::createFromFormat('Y-m-d', '2001-07-15')->age,
            ],
            '3' => [
                'rc' => '975406/2494',
                'gender' => Gender::FEMALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '1997-04-06'),
                'age' => Carbon::createFromFormat('Y-m-d', '1997-04-06')->age,
            ],
            '4' => [
                'rc' => '956022/6027',
                'gender' => Gender::FEMALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '1995-10-22'),
                'age' => Carbon::createFromFormat('Y-m-d', '1995-10-22')->age,
            ],
            '5' => [
                'rc' => '960326/2955',
                'gender' => Gender::MALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '1996-03-26'),
                'age' => Carbon::createFromFormat('Y-m-d', '1996-03-26')->age,
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
            $citizen = Socrates::getCitizenDataFromId($person['rc'], 'CZ');

            $this->assertEquals($person['gender'], $citizen->getGender());
            $this->assertEquals($person['dob'], $citizen->getDateOfBirth());
            $this->assertEquals($person['age'], $citizen->getAge());
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::validateId('88606/875', 'CZ');
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->people as $person) {
            $this->assertTrue(
                Socrates::validateId($person['rc'], 'CZ')
            );
        }

        foreach ($this->invalidIds as $rc) {
            $this->assertFalse(
                Socrates::validateId($rc, 'CZ')
            );
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::validateId('88606/875', 'CZ');
    }

}