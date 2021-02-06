<?php

namespace Reducktion\Socrates\Tests\Feature\Europe;

use Carbon\Carbon;
use DateTime;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Laravel\Facades\Socrates;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Tests\Feature\FeatureTest;

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
                'dob' => new DateTime('1993-10-27'),
                'age' => $this->calculateAge(new DateTime('1993-10-27')),
            ],
            'Miroslav' => [
                'rc' => '000816/9733',
                'gender' => Gender::MALE,
                'dob' => new DateTime('2000-08-16'),
                'age' => $this->calculateAge(new DateTime('2000-08-16')),
            ],
            'Natalia' => [
                'rc' => '015612/5552',
                'gender' => Gender::FEMALE,
                'dob' => new DateTime('2001-06-12'),
                'age' => $this->calculateAge(new DateTime('2001-06-12')),
            ],
            'Victoria' => [
                'rc' => '935103/6189',
                'gender' => Gender::FEMALE,
                'dob' => new DateTime('1993-01-03'),
                'age' => $this->calculateAge(new DateTime('1993-01-03')),
            ],
            'Jožko' => [
                'rc' => '010722/4634',
                'gender' => Gender::MALE,
                'dob' => new DateTime('2001-07-22'),
                'age' => $this->calculateAge(new DateTime('2001-07-22')),
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

            self::assertEquals($person['gender'], $citizen->getGender());
            self::assertEquals(Carbon::instance($person['dob']), $citizen->getDateOfBirth());
            self::assertEquals($person['dob'], $citizen->getDateOfBirthNative());
            self::assertEquals($person['age'], $citizen->getAge());
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::validateId('88606/875', 'SK');
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->people as $person) {
            self::assertTrue(
                Socrates::validateId($person['rc'], 'SK')
            );
        }

        foreach ($this->invalidIds as $rc) {
            self::assertFalse(
                Socrates::validateId($rc, 'SK')
            );
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::validateId('88606/875', 'SK');
    }
}
