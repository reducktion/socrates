<?php

namespace Reducktion\Socrates\Tests\Feature\Europe;

use DateTime;
use Reducktion\Socrates\Constants\Country;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Exceptions\UnsupportedOperationException;
use Reducktion\Socrates\Tests\Feature\FeatureTest;

class LatviaTest extends FeatureTest
{
    private array $supportedExtractionPeople;
    private array $unsupportedExtractionPeople;
    private array $invalidIds;

    protected function setUp(): void
    {
        parent::setUp();

        $this->supportedExtractionPeople = [
            'Agnese' => [
                'pk' => '120673-10053',
                'dob' => new DateTime('1973-06-12'),
                'age' => $this->calculateAge(new DateTime('1973-06-12')),
            ],
            'Rainers' => [
                'pk' => '031098-10386',
                'dob' => new DateTime('1998-10-03'),
                'age' => $this->calculateAge(new DateTime('1998-10-03')),
            ],
            'Kin' => [
                'pk' => '250302-20559',
                'dob' => new DateTime('2002-03-25'),
                'age' => $this->calculateAge(new DateTime('2002-03-25')),
            ],
            'Anton' => [
                'pk' => '300863-10955',
                'dob' => new DateTime('1963-08-30'),
                'age' => $this->calculateAge(new DateTime('1963-08-30')),
            ],
            'Karlis' => [
                'pk' => '171210-20739',
                'dob' => new DateTime('2010-12-17'),
                'age' => $this->calculateAge(new DateTime('2010-12-17')),
            ]
        ];

        $this->unsupportedExtractionPeople = [
            'Barbara' => [
                'pk' => '326587-98143',
            ],
            'Rualds' => [
                'pk' => '328291-18212',
            ],
            'Safeena' => [
                'pk' => '320112-73198',
            ],
            'Martin' => [
                'pk' => '329121-01023',
            ],
            'Kristiana' => [
                'pk' => '328891-74201',
            ]
        ];

        $this->invalidIds = [
            '110801-87212',
            '240799-92123',
            '998822-97621',
            '412678-12362',
            '987212-17538',
        ];
    }

    public function test_extract_behaviour(): void
    {
        foreach ($this->supportedExtractionPeople as $person) {
            $citizen = $this->socrates->getCitizenDataFromId($person['pk'], Country::Latvia);
            self::assertEquals($person['dob'], $citizen->getDateOfBirth());
            self::assertEquals($person['age'], $citizen->getAge());
        }

        $this->expectException(UnsupportedOperationException::class);
        foreach ($this->unsupportedExtractionPeople as $person) {
            $this->socrates->getCitizenDataFromId($person['pk'], Country::Latvia);
        }

        $this->expectException(InvalidLengthException::class);

        $this->socrates->getCitizenDataFromId('326587-981', Country::Latvia);
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->supportedExtractionPeople as $person) {
            self::assertTrue(
                $this->socrates->validateId($person['pk'], Country::Latvia)
            );
        }

        foreach ($this->invalidIds as $pk) {
            self::assertFalse(
                $this->socrates->validateId($pk, Country::Latvia)
            );
        }

        $this->expectException(InvalidLengthException::class);

        $this->socrates->validateId('171210-2073', Country::Latvia);
    }
}
