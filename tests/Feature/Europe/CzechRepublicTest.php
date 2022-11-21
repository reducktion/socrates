<?php

namespace Reducktion\Socrates\Tests\Feature\Europe;

use DateTime;
use Reducktion\Socrates\Constants\Country;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Tests\Feature\FeatureTest;

class CzechRepublicTest extends FeatureTest
{
    private array $people;
    private array $invalidIds;

    protected function setUp(): void
    {
        parent::setUp();

        $this->people = [
            'Michal' => [
                'rc' => '990224/9258',
                'gender' => Gender::Male,
                'dob' => new DateTime('1999-02-24'),
                'age' => $this->calculateAge(new DateTime('1999-02-24')),
            ],
            'Tereza' => [
                'rc' => '0157155328',
                'gender' => Gender::Female,
                'dob' => new DateTime('2001-07-15'),
                'age' => $this->calculateAge(new DateTime('2001-07-15')),
            ],
            'Adéla' => [
                'rc' => '975406/2494',
                'gender' => Gender::Female,
                'dob' => new DateTime('1997-04-06'),
                'age' => $this->calculateAge(new DateTime('1997-04-06')),
            ],
            'Lucie' => [
                'rc' => '956022/6027',
                'gender' => Gender::Female,
                'dob' => new DateTime('1995-10-22'),
                'age' => $this->calculateAge(new DateTime('1995-10-22')),
            ],
            'Petr' => [
                'rc' => '960326/2955',
                'gender' => Gender::Male,
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
            $citizen = $this->socrates->getCitizenDataFromId($person['rc'], Country::CzechRepublic);

            self::assertEquals($person['gender'], $citizen->getGender());
            self::assertEquals($person['dob'], $citizen->getDateOfBirth());
            self::assertEquals($person['age'], $citizen->getAge());
        }

        $this->expectException(InvalidLengthException::class);

        $this->socrates->validateId('88606/875', Country::CzechRepublic);
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->people as $person) {
            self::assertTrue(
                $this->socrates->validateId($person['rc'], Country::CzechRepublic)
            );
        }

        foreach ($this->invalidIds as $rc) {
            self::assertFalse(
                $this->socrates->validateId($rc, Country::CzechRepublic)
            );
        }

        $this->expectException(InvalidLengthException::class);

        $this->socrates->validateId('88606/875', Country::CzechRepublic);
    }
}
