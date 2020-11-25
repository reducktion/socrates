<?php

namespace Reducktion\Socrates\Tests\Feature\Europe;

use Carbon\Carbon;
use DateTime;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Laravel\Facades\Socrates;
use Reducktion\Socrates\Tests\Feature\FeatureTest;

class IcelandTest extends FeatureTest
{
    private $people;
    private $invalidIds;

    protected function setUp(): void
    {
        parent::setUp();

        $this->people = [
            'andi' => [
                'kt' => '0902862349',
                'dob' => new DateTime('1986-02-09'),
                'age' => $this->calculateAge(new DateTime('1986-02-09')),
            ],
            'freyja' => [
                'kt' => '120174-3399',
                'dob' => new DateTime('1974-01-12'),
                'age' => $this->calculateAge(new DateTime('1974-01-12')),
            ],
            'nair' => [
                'kt' => '1808905059',
                'dob' => new DateTime('1990-08-18'),
                'age' => $this->calculateAge(new DateTime('1990-08-18')),
            ],
            'eva' => [
                'kt' => '2008108569',
                'dob' => new DateTime('1910-08-20'),
                'age' => $this->calculateAge(new DateTime('1910-08-20')),
            ],
            'hrafn' => [
                'kt' => '100303-4930',
                'dob' => new DateTime('2003-03-10'),
                'age' => $this->calculateAge(new DateTime('2003-03-10')),
            ],
        ];

        $this->invalidIds = [
            '2343212454',
            '333694-0034',
            '1201743389',
            '0902862549',
            '0404047054'
        ];
    }

    public function test_extract_behaviour(): void
    {
        foreach ($this->people as $person) {
            $citizen = Socrates::getCitizenDataFromId($person['kt'], 'IS');

            self::assertEquals(Carbon::instance($person['dob']), $citizen->getDateOfBirth());
            self::assertEquals($person['dob'], $citizen->getDateOfBirthNative());
            self::assertEquals($person['age'], $citizen->getAge());
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::getCitizenDataFromId('324432-343', 'IS');
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->people as $person) {
            self::assertTrue(
                Socrates::validateId($person['kt'], 'IS')
            );
        }

        foreach ($this->invalidIds as $kt) {
            self::assertFalse(
                Socrates::validateId($kt, 'IS')
            );
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::validateId('21442411', 'IS');
    }
}
