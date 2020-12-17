<?php

namespace Reducktion\Socrates\Tests\Feature\Europe;

use Carbon\Carbon;
use DateTime;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Laravel\Facades\Socrates;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Tests\Feature\FeatureTest;

class LithuaniaTest extends FeatureTest
{
    private $people;
    private $invalidIds;

    public function setUp(): void
    {
        parent::setUp();

        $this->people = [
            'janis' => [
                'ak' => '38409152012',
                'gender' => Gender::MALE,
                'dob' => new DateTime('1984-09-15'),
                'age' => $this->calculateAge(new DateTime('1984-09-15')),
            ],
            'natas' => [
                'ak' => '31710058023',
                'gender' => Gender::MALE,
                'dob' => new DateTime('1917-10-05'),
                'age' => $this->calculateAge(new DateTime('1917-10-05')),
            ],
            'daiva' => [
                'ak' => '44804129713',
                'gender' => Gender::FEMALE,
                'dob' => new DateTime('1948-04-12'),
                'age' => $this->calculateAge(new DateTime('1948-04-12')),
            ],
            'geta' => [
                'ak' => '60607279626',
                'gender' => Gender::FEMALE,
                'dob' => new DateTime('2006-07-27'),
                'age' => $this->calculateAge(new DateTime('2006-07-27')),
            ],
            'domynikas' => [
                'ak' => '50508199254',
                'gender' => Gender::MALE,
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
            $citizen = Socrates::getCitizenDataFromId($person['ak'], 'LT');
            self::assertEquals($person['gender'], $citizen->getGender());
            self::assertEquals(Carbon::instance($person['dob']), $citizen->getDateOfBirth());
            self::assertEquals($person['dob'], $citizen->getDateOfBirthNative());
            self::assertEquals($person['age'], $citizen->getAge());
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::getCitizenDataFromId('3050811811', 'LT');
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->people as $person) {
            self::assertTrue(
                Socrates::validateId($person['ak'], 'LT')
            );
        }

        foreach ($this->invalidIds as $ak) {
            self::assertFalse(
                Socrates::validateId($ak, 'LT')
            );
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::validateId('3050811811123', 'LT');
    }
}
