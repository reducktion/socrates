<?php

namespace Reducktion\Socrates\Tests\Feature\Europe;

use Carbon\Carbon;
use DateTime;
use Reducktion\Socrates\Laravel\Facades\Socrates;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Tests\Feature\FeatureTest;

class LuxembourgTest extends FeatureTest
{
    private $people;
    private $invalidIds;

    protected function setUp(): void
    {
        parent::setUp();

        $this->people = [
            'Gabriel' => [
                'nin' => '1983081246783',
                'dob' => new DateTime('1983-08-12'),
                'age' => $this->calculateAge(new DateTime('1983-08-12')),
            ],
            'Emma' => [
                'nin' => '2003042581931',
                'dob' => new DateTime('2003-04-25'),
                'age' => $this->calculateAge(new DateTime('2003-04-25')),
            ],
            'Leo' => [
                'nin' => '1971110258746',
                'dob' => new DateTime('1971-11-02'),
                'age' => $this->calculateAge(new DateTime('1971-11-02')),
            ],
            'Lara' => [
                'nin' => '2012051469336',
                'dob' => new DateTime('2012-05-14'),
                'age' => $this->calculateAge(new DateTime('2012-05-14')),
            ],
            'Noah' => [
                'nin' => '1994092874551',
                'dob' => new DateTime('1994-09-28'),
                'age' => $this->calculateAge(new DateTime('1994-09-28')),
            ],
        ];

        $this->invalidIds = [
            '1994789587182',
            '5971654782313',
            '2055101054879',
            '1997053045687',
            '2001111123654',
        ];
    }

    public function test_extract_behaviour(): void
    {
        foreach ($this->people as $person) {
            $citizen = Socrates::getCitizenDataFromId($person['nin'], 'LU');
            self::assertEquals(Carbon::instance($person['dob']), $citizen->getDateOfBirth());
            self::assertEquals($person['dob'], $citizen->getDateOfBirthNative());
            self::assertEquals($person['age'], $citizen->getAge());
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::getCitizenDataFromId('1983046783', 'LU');
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->people as $person) {
            self::assertTrue(
                Socrates::validateId($person['nin'], 'LU')
            );
        }

        foreach ($this->invalidIds as $nin) {
            self::assertFalse(
                Socrates::validateId($nin, 'LU')
            );
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::validateId('1983046783', 'LU');
    }
}
