<?php

namespace Reducktion\Socrates\Tests\Feature\Europe;

use DateTime;
use Reducktion\Socrates\Constants\Country;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Tests\Feature\FeatureTest;

class DenmarkTest extends FeatureTest
{
    private array $people;
    private array $invalidIds;

    protected function setUp(): void
    {
        parent::setUp();

        $this->people = [
            'julius' => [
                'cpr' => '090792-1395',
                'gender' => Gender::Male,
                'dob' => new DateTime('1992-07-09'),
                'age' => $this->calculateAge(new DateTime('1992-07-09')),
            ],
            'naja' => [
                'cpr' => '070593-0600',
                'gender' => Gender::Female,
                'dob' => new DateTime('1993-05-07'),
                'age' => $this->calculateAge(new DateTime('1993-05-07')),
            ],
            'rolla' => [
                'cpr' => '150437-3068',
                'gender' => Gender::Female,
                'dob' => new DateTime('1937-04-15'),
                'age' => $this->calculateAge(new DateTime('1937-04-15')),
            ],
            'thomas' => [
                'cpr' => '160888-1995',
                'gender' => Gender::Male,
                'dob' => new DateTime('1988-08-16'),
                'age' => $this->calculateAge(new DateTime('1988-08-16')),
            ],
            'mia' => [
                'cpr' => '040404-7094',
                'gender' => Gender::Female,
                'dob' => new DateTime('2004-04-04'),
                'age' => $this->calculateAge(new DateTime('2004-04-04')),
            ],
        ];

        $this->invalidIds = [
            '234321-2454',
            '333694-0034',
            '088383-2313',
            '133232-1323',
            '040404-7054'
        ];
    }

    public function test_extract_behaviour(): void
    {
        foreach ($this->people as $person) {
            $citizen = $this->socrates->getCitizenDataFromId($person['cpr'], Country::Denmark);

            self::assertEquals($person['gender'], $citizen->getGender());
            self::assertEquals($person['dob'], $citizen->getDateOfBirth());
            self::assertEquals($person['age'], $citizen->getAge());
        }

        $this->expectException(InvalidLengthException::class);

        $this->socrates->getCitizenDataFromId('324432-343', Country::Denmark);
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->people as $person) {
            self::assertTrue(
                $this->socrates->validateId($person['cpr'], Country::Denmark)
            );
        }

        foreach ($this->invalidIds as $cpr) {
            self::assertFalse(
                $this->socrates->validateId($cpr, Country::Denmark)
            );
        }

        $this->expectException(InvalidLengthException::class);

        $this->socrates->validateId('21442-2411', Country::Denmark);
    }
}
