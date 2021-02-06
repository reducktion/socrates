<?php

namespace Reducktion\Socrates\Tests\Feature\Europe;

use Carbon\Carbon;
use DateTime;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Laravel\Facades\Socrates;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Tests\Feature\FeatureTest;

class NorwayTest extends FeatureTest
{
    private $people;
    private $invalidIds;

    protected function setUp(): void
    {
        parent::setUp();

        $this->people = [
            'kristoffer' => [
                'fn' => '05080176785',
                'gender' => Gender::MALE,
                'dob' => new DateTime('2001-08-05'),
                'age' => $this->calculateAge(new DateTime('2001-08-05')),
            ],
            'astrid' => [
                'fn' => '20050761232',
                'gender' => Gender::FEMALE,
                'dob' => new DateTime('2007-05-20'),
                'age' => $this->calculateAge(new DateTime('2007-05-20')),
            ],
            'linn' => [
                'fn' => '28094949248',
                'gender' => Gender::FEMALE,
                'dob' => new DateTime('1949-09-28'),
                'age' => $this->calculateAge(new DateTime('1949-09-28')),
            ],
            'terje' => [
                'fn' => '14019513913',
                'gender' => Gender::MALE,
                'dob' => new DateTime('1995-01-14'),
                'age' => $this->calculateAge(new DateTime('1995-01-14')),
            ],
            'heidi' => [
                'fn' => '01090749036',
                'gender' => Gender::FEMALE,
                'dob' => new DateTime('1907-09-01'),
                'age' => $this->calculateAge(new DateTime('1907-09-01')),
            ],
        ];

        $this->invalidIds = [
            '23432124549',
            '33369400349',
            '08838323133',
            '13323213237',
            '04040470548'
        ];
    }

    public function test_extract_behaviour(): void
    {
        foreach ($this->people as $person) {
            $citizen = Socrates::getCitizenDataFromId($person['fn'], 'NO');

            self::assertEquals($person['gender'], $citizen->getGender());
            self::assertEquals(Carbon::instance($person['dob']), $citizen->getDateOfBirth());
            self::assertEquals($person['dob'], $citizen->getDateOfBirthNative());
            self::assertEquals($person['age'], $citizen->getAge());
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::getCitizenDataFromId('324432343', 'NO');
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->people as $person) {
            self::assertTrue(
                Socrates::validateId($person['fn'], 'NO')
            );
        }

        foreach ($this->invalidIds as $fn) {
            self::assertFalse(
                Socrates::validateId($fn, 'NO')
            );
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::validateId('214422411', 'NO');
    }
}
