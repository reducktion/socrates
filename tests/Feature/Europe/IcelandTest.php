<?php

namespace Reducktion\Socrates\Tests\Feature\Europe;

use DateTime;
use PHPUnit\Framework\Attributes\DataProvider;
use Reducktion\Socrates\Constants\Country;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Tests\Feature\FeatureTestCase;

class IcelandTest extends FeatureTestCase
{
    public static function peopleDataProvider(): array
    {
        return [
            'andi' => [
                'person' => [
                    'kt' => '0902862349',
                    'dob' => new DateTime('1986-02-09'),
                    'age' => self::calculateAge(new DateTime('1986-02-09')),
                ],
            ],
            'freyja' => [
                'person' => [
                    'kt' => '120174-3399',
                    'dob' => new DateTime('1974-01-12'),
                    'age' => self::calculateAge(new DateTime('1974-01-12')),
                ],
            ],
            'nair' => [
                'person' => [
                    'kt' => '1808905059',
                    'dob' => new DateTime('1990-08-18'),
                    'age' => self::calculateAge(new DateTime('1990-08-18')),
                ],
            ],
            'eva' => [
                'person' => [
                    'kt' => '2008108569',
                    'dob' => new DateTime('1910-08-20'),
                    'age' => self::calculateAge(new DateTime('1910-08-20')),
                ],
            ],
            'hrafn' => [
                'person' => [
                    'kt' => '100303-4930',
                    'dob' => new DateTime('2003-03-10'),
                    'age' => self::calculateAge(new DateTime('2003-03-10')),
                ],
            ],
        ];
    }

    public static function invalidIdsDataProvider(): array
    {
        return [
            ['2343212454'],
            ['333694-0034'],
            ['1201743389'],
            ['0902862549'],
            ['0404047054'],
        ];
    }

    #[DataProvider('peopleDataProvider')]
    public function test_extract_behaviour(array $person): void
    {
        $citizen = $this->socrates->getCitizenDataFromId($person['kt'], Country::Iceland);
        self::assertEquals($person['dob'], $citizen->getDateOfBirth());
        self::assertEquals($person['age'], $citizen->getAge());
    }

    #[DataProvider('peopleDataProvider')]
    public function test_validation_with_valid_ids_passes(array $person): void
    {
        self::assertTrue($this->socrates->validateId($person['kt'], Country::Iceland));
    }

    #[DataProvider('invalidIdsDataProvider')]
    public function test_validation_with_invalid_ids_fails(string $invalidId): void
    {
        self::assertFalse($this->socrates->validateId($invalidId, Country::Iceland));
    }

    public function test_validation_using_ids_with_invalid_length_throw_exception(): void
    {
        $this->expectException(InvalidLengthException::class);

        $this->socrates->validateId('21442411', Country::Iceland);
    }
}
