<?php

namespace Reducktion\Socrates\Tests\Feature\Europe;

use Carbon\Carbon;
use DateTime;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Laravel\Facades\Socrates;
use Reducktion\Socrates\Tests\Feature\FeatureTest;

class DenmarkTest extends FeatureTest
{
    private $people;
    private $invalidIds;

    protected function setUp(): void
    {
        parent::setUp();

        $this->people = [
            'julius' => [
                'cpr' => '090792-1395',
                'gender' => Gender::MALE,
                'dob' => new DateTime('1992-07-09'),
                'age' => $this->calculateAge(new DateTime('1992-07-09')),
            ],
            'naja' => [
                'cpr' => '070593-0600',
                'gender' => Gender::FEMALE,
                'dob' => new DateTime('1993-05-07'),
                'age' => $this->calculateAge(new DateTime('1993-05-07')),
            ],
            'rolla' => [
                'cpr' => '150437-3068',
                'gender' => Gender::FEMALE,
                'dob' => new DateTime('1937-04-15'),
                'age' => $this->calculateAge(new DateTime('1937-04-15')),
            ],
            'thomas' => [
                'cpr' => '160888-1995',
                'gender' => Gender::MALE,
                'dob' => new DateTime('1988-08-16'),
                'age' => $this->calculateAge(new DateTime('1988-08-16')),
            ],
            'mia' => [
                'cpr' => '040404-7094',
                'gender' => Gender::FEMALE,
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
            $citizen = Socrates::getCitizenDataFromId($person['cpr'], 'DK');

            self::assertEquals($person['gender'], $citizen->getGender());
            self::assertEquals(Carbon::instance($person['dob']), $citizen->getDateOfBirth());
            self::assertEquals($person['dob'], $citizen->getDateOfBirthNative());
            self::assertEquals($person['age'], $citizen->getAge());
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::getCitizenDataFromId('324432-343', 'DK');
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->people as $person) {
            self::assertTrue(
                Socrates::validateId($person['cpr'], 'DK')
            );
        }

        foreach ($this->invalidIds as $cpr) {
            self::assertFalse(
                Socrates::validateId($cpr, 'DK')
            );
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::validateId('21442-2411', 'DK');
    }
}
