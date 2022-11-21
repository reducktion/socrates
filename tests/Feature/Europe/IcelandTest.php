<?php

namespace Reducktion\Socrates\Tests\Feature\Europe;

use DateTime;
use Reducktion\Socrates\Constants\Country;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Tests\Feature\FeatureTest;

class IcelandTest extends FeatureTest
{
    private array $people;
    private array $invalidIds;

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
            $citizen = $this->socrates->getCitizenDataFromId($person['kt'], Country::Iceland);

            self::assertEquals($person['dob'], $citizen->getDateOfBirth());
            self::assertEquals($person['age'], $citizen->getAge());
        }

        $this->expectException(InvalidLengthException::class);

        $this->socrates->getCitizenDataFromId('324432-343', Country::Iceland);
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->people as $person) {
            self::assertTrue(
                $this->socrates->validateId($person['kt'], Country::Iceland)
            );
        }

        foreach ($this->invalidIds as $kt) {
            self::assertFalse(
                $this->socrates->validateId($kt, Country::Iceland)
            );
        }

        $this->expectException(InvalidLengthException::class);

        $this->socrates->validateId('21442411', Country::Iceland);
    }
}
