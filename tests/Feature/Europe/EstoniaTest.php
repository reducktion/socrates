<?php

namespace Reducktion\Socrates\Tests\Feature\Europe;

use Carbon\Carbon;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Laravel\Facades\Socrates;
use Reducktion\Socrates\Tests\Feature\FeatureTest;

class EstoniaTest extends FeatureTest
{
    private $people;
    private $invalidIds;

    protected function setUp(): void
    {
        parent::setUp();

        $this->people = [
            'Grete' => [
                'ik' => '48004119745',
                'gender' => Gender::FEMALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '1980-04-11'),
                'age' => Carbon::createFromFormat('Y-m-d', '1980-04-11')->age,
            ],
            'Kaarel' => [
                'ik' => '50108040021',
                'gender' => Gender::MALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '2001-08-04'),
                'age' => Carbon::createFromFormat('Y-m-d', '2001-08-04')->age,
            ],
            'Seb' => [
                'ik' => '36910180118',
                'gender' => Gender::MALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '1969-10-18'),
                'age' => Carbon::createFromFormat('Y-m-d', '1969-10-18')->age,
            ],
            'Jakob' => [
                'ik' => '38601230129',
                'gender' => Gender::MALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '1986-01-23'),
                'age' => Carbon::createFromFormat('Y-m-d', '1986-01-23')->age,
            ],
            'Katarina' => [
                'ik' => '60310275631',
                'gender' => Gender::FEMALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '2003-10-27'),
                'age' => Carbon::createFromFormat('Y-m-d', '2003-10-27')->age,
            ],
        ];

        $this->invalidIds = [
            '88732230129',
            '12345630129',
            '38608192637',
            '10293846198',
            '12309132708'
        ];
    }

    public function test_extract_behaviour(): void
    {
        foreach ($this->people as $person) {
            $citizen = Socrates::getCitizenDataFromId($person['ik'], 'EE');
            $this->assertEquals($person['gender'], $citizen->getGender());
            $this->assertEquals($person['dob'], $citizen->getDateOfBirth());
            $this->assertEquals($person['age'], $citizen->getAge());
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::getCitizenDataFromId('3860123012', 'EE');
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->people as $person) {
            $this->assertTrue(
                Socrates::validateId($person['ik'], 'EE')
            );
        }

        foreach ($this->invalidIds as $ik) {
            $this->assertFalse(
                Socrates::validateId($ik, 'EE')
            );
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::validateId('6031027563', 'EE');
    }
}
