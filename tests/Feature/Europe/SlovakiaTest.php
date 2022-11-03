<?php

namespace Reducktion\Socrates\Tests\Feature\Europe;

use DateTime;
use Reducktion\Socrates\Constants\Country;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Tests\Feature\FeatureTest;

class SlovakiaTest extends FeatureTest
{
    private array $people;
    private array $invalidIds;

    protected function setUp(): void
    {
        parent::setUp();

        $this->people = [
            'Boris' => [
                'rc' => '931027/3951',
                'gender' => Gender::Male,
                'dob' => new DateTime('1993-10-27'),
                'age' => $this->calculateAge(new DateTime('1993-10-27')),
            ],
            'Miroslav' => [
                'rc' => '000816/9733',
                'gender' => Gender::Male,
                'dob' => new DateTime('2000-08-16'),
                'age' => $this->calculateAge(new DateTime('2000-08-16')),
            ],
            'Natalia' => [
                'rc' => '015612/5552',
                'gender' => Gender::Female,
                'dob' => new DateTime('2001-06-12'),
                'age' => $this->calculateAge(new DateTime('2001-06-12')),
            ],
            'Victoria' => [
                'rc' => '935103/6189',
                'gender' => Gender::Female,
                'dob' => new DateTime('1993-01-03'),
                'age' => $this->calculateAge(new DateTime('1993-01-03')),
            ],
            'Jožko' => [
                'rc' => '010722/4634',
                'gender' => Gender::Male,
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
            $citizen = $this->socrates->getCitizenDataFromId($person['rc'], Country::Slovakia);

            self::assertEquals($person['gender'], $citizen->getGender());
            self::assertEquals($person['dob'], $citizen->getDateOfBirth());
            self::assertEquals($person['age'], $citizen->getAge());
        }

        $this->expectException(InvalidLengthException::class);

        $this->socrates->validateId('88606/875', Country::Slovakia);
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->people as $person) {
            self::assertTrue(
                $this->socrates->validateId($person['rc'], Country::Slovakia)
            );
        }

        foreach ($this->invalidIds as $rc) {
            self::assertFalse(
                $this->socrates->validateId($rc, Country::Slovakia)
            );
        }

        $this->expectException(InvalidLengthException::class);

        $this->socrates->validateId('88606/875', Country::Slovakia);
    }
}
