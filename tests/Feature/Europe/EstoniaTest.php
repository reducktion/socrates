<?php

namespace Reducktion\Socrates\Tests\Feature\Europe;

use Carbon\Carbon;
use DateTime;
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
                'dob' => new DateTime('1980-04-11'),
                'age' => $this->calculateAge(new DateTime('1980-04-11')),
            ],
            'Kaarel' => [
                'ik' => '50108040021',
                'gender' => Gender::MALE,
                'dob' => new DateTime('2001-08-04'),
                'age' => $this->calculateAge(new DateTime('2001-08-04')),
            ],
            'Seb' => [
                'ik' => '36910180118',
                'gender' => Gender::MALE,
                'dob' => new DateTime('1969-10-18'),
                'age' => $this->calculateAge(new DateTime('1969-10-18')),
            ],
            'Jakob' => [
                'ik' => '38601230129',
                'gender' => Gender::MALE,
                'dob' => new DateTime('1986-01-23'),
                'age' => $this->calculateAge(new DateTime('1986-01-23')),
            ],
            'Katarina' => [
                'ik' => '60310275631',
                'gender' => Gender::FEMALE,
                'dob' => new DateTime('2003-10-27'),
                'age' => $this->calculateAge(new DateTime('2003-10-27')),
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
            self::assertEquals($person['gender'], $citizen->getGender());
            self::assertEquals(Carbon::instance($person['dob']), $citizen->getDateOfBirth());
            self::assertEquals($person['dob'], $citizen->getDateOfBirthNative());
            self::assertEquals($person['age'], $citizen->getAge());
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::getCitizenDataFromId('3860123012', 'EE');
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->people as $person) {
            self::assertTrue(
                Socrates::validateId($person['ik'], 'EE')
            );
        }

        foreach ($this->invalidIds as $ik) {
            self::assertFalse(
                Socrates::validateId($ik, 'EE')
            );
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::validateId('6031027563', 'EE');
    }
}
