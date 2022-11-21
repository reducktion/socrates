<?php

namespace Reducktion\Socrates\Tests\Feature\Europe;

use DateTime;
use Reducktion\Socrates\Constants\Country;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Tests\Feature\FeatureTest;

class LithuaniaTest extends FeatureTest
{
    private array $people;
    private array $invalidIds;

    public function setUp(): void
    {
        parent::setUp();

        $this->people = [
            'janis' => [
                'ak' => '38409152012',
                'gender' => Gender::Male,
                'dob' => new DateTime('1984-09-15'),
                'age' => $this->calculateAge(new DateTime('1984-09-15')),
            ],
            'natas' => [
                'ak' => '31710058023',
                'gender' => Gender::Male,
                'dob' => new DateTime('1917-10-05'),
                'age' => $this->calculateAge(new DateTime('1917-10-05')),
            ],
            'daiva' => [
                'ak' => '44804129713',
                'gender' => Gender::Female,
                'dob' => new DateTime('1948-04-12'),
                'age' => $this->calculateAge(new DateTime('1948-04-12')),
            ],
            'geta' => [
                'ak' => '60607279626',
                'gender' => Gender::Female,
                'dob' => new DateTime('2006-07-27'),
                'age' => $this->calculateAge(new DateTime('2006-07-27')),
            ],
            'domynikas' => [
                'ak' => '50508199254',
                'gender' => Gender::Male,
                'dob' => new DateTime('2005-08-19'),
                'age' => $this->calculateAge(new DateTime('2005-08-19')),
            ]
        ];

        $this->invalidIds = [
            '38409152011',
            '10501199256',
            '60607279621',
            '10602279622',
            '31744053021'
        ];
    }

    public function test_extract_behaviour(): void
    {
        foreach ($this->people as $person) {
            $citizen = $this->socrates->getCitizenDataFromId($person['ak'], Country::Lithuania);
            self::assertEquals($person['gender'], $citizen->getGender());
            self::assertEquals($person['dob'], $citizen->getDateOfBirth());
            self::assertEquals($person['age'], $citizen->getAge());
        }

        $this->expectException(InvalidLengthException::class);

        $this->socrates->getCitizenDataFromId('3050811811', Country::Lithuania);
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->people as $person) {
            self::assertTrue(
                $this->socrates->validateId($person['ak'], Country::Lithuania)
            );
        }

        foreach ($this->invalidIds as $ak) {
            self::assertFalse(
                $this->socrates->validateId($ak, Country::Lithuania)
            );
        }

        $this->expectException(InvalidLengthException::class);

        $this->socrates->validateId('3050811811123', Country::Lithuania);
    }
}
