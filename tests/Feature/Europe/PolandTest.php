<?php

namespace Reducktion\Socrates\Tests\Feature\Europe;

use Carbon\Carbon;
use DateTime;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Laravel\Facades\Socrates;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Tests\Feature\FeatureTest;

class PolandTest extends FeatureTest
{
    private $people;
    private $invalidIds;

    protected function setUp(): void
    {
        parent::setUp();

        $this->people = [
            'Alesky' => [
                'pesel' => '91072592137',
                'gender' => Gender::MALE,
                'dob' => new DateTime('1991-07-25'),
                'age' => $this->calculateAge(new DateTime('1991-07-25')),
            ],
            'Adelajda' => [
                'pesel' => '02220826789',
                'gender' => Gender::FEMALE,
                'dob' => new DateTime('2002-02-08'),
                'age' => $this->calculateAge(new DateTime('2002-02-08')),
            ],
            'Izolda' => [
                'pesel' => '87050832348',
                'gender' => Gender::FEMALE,
                'dob' => new DateTime('1987-05-08'),
                'age' => $this->calculateAge(new DateTime('1987-05-08')),
            ],
            'Klaudiusz' => [
                'pesel' => '87012962775',
                'gender' => Gender::MALE,
                'dob' => new DateTime('1987-01-29'),
                'age' => $this->calculateAge(new DateTime('1987-01-29')),
            ],
            'Fryderyk' => [
                'pesel' => '64032906019',
                'gender' => Gender::MALE,
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
            $citizen = Socrates::getCitizenDataFromId($person['pesel'], 'PL');
            self::assertEquals($person['gender'], $citizen->getGender());
            self::assertEquals(Carbon::instance($person['dob']), $citizen->getDateOfBirth());
            self::assertEquals($person['dob'], $citizen->getDateOfBirthNative());
            self::assertEquals($person['age'], $citizen->getAge());
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::validateId('5882764108', 'PL');
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->people as $person) {
            self::assertTrue(
                Socrates::validateId($person['pesel'], 'PL')
            );
        }

        foreach ($this->invalidIds as $pesel) {
            self::assertFalse(
                Socrates::validateId($pesel, 'PL')
            );
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::validateId('4810164188', 'PL');
    }
}
