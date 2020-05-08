<?php

namespace Reducktion\Socrates\Tests\Feature;

use Carbon\Carbon;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Facades\Socrates;

class UkraineTest extends FeatureTest
{
    private $people;
    private $invalidIds;

    protected function setUp(): void
    {
        parent::setUp();

        $this->people = [
            'petro' => [
                'id' => '4031090675',
                'gender' => Gender::MALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '2010-05-12'),
                'age' => Carbon::createFromFormat('Y-m-d', '2010-05-12')->age
            ],
            'vadym' => [
                'id' => '3292197434',
                'gender' => Gender::MALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '1990-02-17'),
                'age' => Carbon::createFromFormat('Y-m-d', '1990-02-17')->age
            ],
            'veronika' => [
                'id' => '2023599602',
                'gender' => Gender::FEMALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '1955-05-26'),
                'age' => Carbon::createFromFormat('Y-m-d', '1955-05-26')->age
            ],
            'ruslana' => [
                'id' => '4247134484',
                'gender' => Gender::FEMALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '2016-04-11'),
                'age' => Carbon::createFromFormat('Y-m-d', '2016-04-11')->age
            ],
            'zoya' => [
                'id' => '3771509002',
                'gender' => Gender::FEMALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '2003-04-04'),
                'age' => Carbon::createFromFormat('Y-m-d', '2003-04-04')->age
            ]
        ];

        $this->invalidIds = [
            '3771509022',
            '3771593429',
            '3771542376',
            '0023577230',
            '3000077230'
        ];
    }

    public function test_extract_behaviour(): void
    {
        foreach ($this->people as $person) {
            $citizen = Socrates::getCitizenDataFromId($person['id'], 'UA');

            $this->assertEquals($person['gender'], $citizen->getGender());
            $this->assertEquals($person['dob'], $citizen->getDateOfBirth());
            $this->assertEquals($person['age'], $citizen->getAge());
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::getCitizenDataFromId('37715090021', 'UA');
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->people as $person) {
            $this->assertTrue(
                Socrates::validateId($person['id'], 'UA')
            );
        }

        foreach ($this->invalidIds as $id) {
            $this->assertFalse(
                Socrates::validateId($id, 'UA')
            );
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::validateId('37715090022', 'UA');
    }
}