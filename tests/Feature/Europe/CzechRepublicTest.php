<?php

namespace Reducktion\Socrates\Tests\Feature\Europe;

use Carbon\Carbon;
use DateTime;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Laravel\Facades\Socrates;
use Reducktion\Socrates\Tests\Feature\FeatureTest;

class CzechRepublicTest extends FeatureTest
{
    private $people;
    private $invalidIds;

    protected function setUp(): void
    {
        parent::setUp();

        $this->people = [
            'Michal' => [
                'rc' => '990224/9258',
                'gender' => Gender::MALE,
                'dob' => new DateTime('1999-02-24'),
                'age' => $this->calculateAge(new DateTime('1999-02-24')),
            ],
            'Tereza' => [
                'rc' => '0157155328',
                'gender' => Gender::FEMALE,
                'dob' => new DateTime('2001-07-15'),
                'age' => $this->calculateAge(new DateTime('2001-07-15')),
            ],
            'Adéla' => [
                'rc' => '975406/2494',
                'gender' => Gender::FEMALE,
                'dob' => new DateTime('1997-04-06'),
                'age' => $this->calculateAge(new DateTime('1997-04-06')),
            ],
            'Lucie' => [
                'rc' => '956022/6027',
                'gender' => Gender::FEMALE,
                'dob' => new DateTime('1995-10-22'),
                'age' => $this->calculateAge(new DateTime('1995-10-22')),
            ],
            'Petr' => [
                'rc' => '960326/2955',
                'gender' => Gender::MALE,
                'dob' => new DateTime('1996-03-26'),
                'age' => $this->calculateAge(new DateTime('1996-03-26')),
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

            self::assertEquals($person['gender'], $citizen->getGender());
            self::assertEquals(Carbon::instance($person['dob']), $citizen->getDateOfBirth());
            self::assertEquals($person['dob'], $citizen->getDateOfBirthNative());
            self::assertEquals($person['age'], $citizen->getAge());
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::validateId('88606/875', 'CZ');
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->people as $person) {
            self::assertTrue(
                Socrates::validateId($person['rc'], 'CZ')
            );
        }

        foreach ($this->invalidIds as $rc) {
            self::assertFalse(
                Socrates::validateId($rc, 'CZ')
            );
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::validateId('88606/875', 'CZ');
    }
}
