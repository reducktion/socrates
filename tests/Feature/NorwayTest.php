<?php

namespace Reducktion\Socrates\Tests\Feature;

use Carbon\Carbon;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Facades\Socrates;

class NorwayTest extends FeatureTest
{
    private $people;
    private $invalidIds;

    protected function setUp(): void
    {
        parent::setUp();

        $this->people = [
            'kristoffer' => [
                'fn' => '21123426611',
                'gender' => Gender::MALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '1992-07-09'),
                'age' => Carbon::createFromFormat('Y-m-d', '1992-07-09')->age, //18
            ],
            'astrid' => [
                'fn' => '20050761232',
                'gender' => Gender::FEMALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '1993-05-07'),
                'age' => Carbon::createFromFormat('Y-m-d', '1993-05-07')->age, //12
            ],
            'linn' => [
                'fn' => '28094949248',
                'gender' => Gender::FEMALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '1937-04-15'),
                'age' => Carbon::createFromFormat('Y-m-d', '1937-04-15')->age, //70
            ],
            'terje' => [
                'fn' => '14019513913',
                'gender' => Gender::MALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '1988-08-16'),
                'age' => Carbon::createFromFormat('Y-m-d', '1988-08-16')->age, //25
            ],
            'heidi' => [
                'fn' => '01090749036',
                'gender' => Gender::FEMALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '2004-04-04'),
                'age' => Carbon::createFromFormat('Y-m-d', '2004-04-04')->age, //112
            ],
        ];

        $this->invalidIds = [
            '23432124541',
            '33369400340',
            '08838323137',
            '13323213234',
            '04040470543'
        ];
    }

    public function test_extract_behaviour(): void
    {
        foreach ($this->people as $person) {
            $citizen = Socrates::getCitizenDataFromId($person['fn'], 'NO');

            $this->assertEquals($person['gender'], $citizen->getGender());
            $this->assertEquals($person['dob'], $citizen->getDateOfBirth());
            $this->assertEquals($person['age'], $citizen->getAge());
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::getCitizenDataFromId('324432-343', 'NO');
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->people as $person) {
            $this->assertTrue(
                Socrates::validateId($person['fn'], 'NO')
            );
        }

        foreach ($this->invalidIds as $fn) {
            $this->assertFalse(
                Socrates::validateId($fn, 'NO')
            );
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::validateId('214422411', 'NO');
    }
}