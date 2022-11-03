<?php

namespace Reducktion\Socrates\Tests\Feature\Europe;

use DateTime;
use Reducktion\Socrates\Constants\Country;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Tests\Feature\FeatureTest;

class PolandTest extends FeatureTest
{
    private array $people;
    private array $invalidIds;

    protected function setUp(): void
    {
        parent::setUp();

        $this->people = [
            'Alesky' => [
                'pesel' => '91072592137',
                'gender' => Gender::Male,
                'dob' => new DateTime('1991-07-25'),
                'age' => $this->calculateAge(new DateTime('1991-07-25')),
            ],
            'Adelajda' => [
                'pesel' => '02220826789',
                'gender' => Gender::Female,
                'dob' => new DateTime('2002-02-08'),
                'age' => $this->calculateAge(new DateTime('2002-02-08')),
            ],
            'Izolda' => [
                'pesel' => '87050832348',
                'gender' => Gender::Female,
                'dob' => new DateTime('1987-05-08'),
                'age' => $this->calculateAge(new DateTime('1987-05-08')),
            ],
            'Klaudiusz' => [
                'pesel' => '87012962775',
                'gender' => Gender::Male,
                'dob' => new DateTime('1987-01-29'),
                'age' => $this->calculateAge(new DateTime('1987-01-29')),
            ],
            'Fryderyk' => [
                'pesel' => '64032906019',
                'gender' => Gender::Male,
                'dob' => new DateTime('1964-03-29'),
                'age' => $this->calculateAge(new DateTime('1964-03-29')),
            ],
        ];

        $this->invalidIds = [
            '93112056526',
            '04301791498',
            '69042359891',
            '82081349523',
            '48101641888'
        ];
    }

    public function test_extract_behaviour(): void
    {
        foreach ($this->people as $person) {
            $citizen = $this->socrates->getCitizenDataFromId($person['pesel'], Country::Poland);
            self::assertEquals($person['gender'], $citizen->getGender());
            self::assertEquals($person['dob'], $citizen->getDateOfBirth());
            self::assertEquals($person['age'], $citizen->getAge());
        }

        $this->expectException(InvalidLengthException::class);

        $this->socrates->validateId('5882764108', Country::Poland);
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->people as $person) {
            self::assertTrue(
                $this->socrates->validateId($person['pesel'], Country::Poland)
            );
        }

        foreach ($this->invalidIds as $pesel) {
            self::assertFalse(
                $this->socrates->validateId($pesel, Country::Poland)
            );
        }

        $this->expectException(InvalidLengthException::class);

        $this->socrates->validateId('4810164188', Country::Poland);
    }
}
