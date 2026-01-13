<?php

namespace Reducktion\Socrates\Tests\Feature\Europe;

use PHPUnit\Framework\Attributes\DataProvider;
use Reducktion\Socrates\Constants\Country;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Exceptions\UnsupportedOperationException;
use Reducktion\Socrates\Tests\Feature\FeatureTestCase;

class GermanyTest extends FeatureTestCase
{
    public static function extractionDataProvider(): array
    {
        return [
            'unknown' => [
                'person' => [
                    'id' => '81872495633',
                ],
            ],
        ];
    }
    public static function peopleDataProvider(): array
    {
        return [
            'unknown1' => [
                'person' => [
                    'id' => '81872495633',
                ],
            ],
            'unknown2' => [
                'person' => [
                    'id' => '48954371207',
                ],
            ],
            'unknown3' => [
                'person' => [
                    'id' => '55492670836',
                ],
            ],
            'unknown4' => [
                'person' => [
                    'id' => '12345678995',
                ],
            ],
            'unknown5' => [
                'person' => [
                    'id' => '11234567890',
                ],
            ],
        ];
    }

    public static function invalidIdsDataProvider(): array
    {
        return [
            ['01234567812'],
            ['81872495631'],
            ['48954371206'],
            ['55492670834'],
            ['11234567899'],
        ];
    }

    #[DataProvider('extractionDataProvider')]
    public function test_extract_behaviour(array $person): void
    {
        $this->expectException(UnsupportedOperationException::class);

        $this->socrates->getCitizenDataFromId($person['id'], Country::Germany);
    }

    #[DataProvider('peopleDataProvider')]
    public function test_validation_with_valid_ids_passes(array $person): void
    {
        self::assertTrue($this->socrates->validateId($person['id'], Country::Germany));
    }

    #[DataProvider('invalidIdsDataProvider')]
    public function test_validation_with_invalid_ids_fails(string $invalidId): void
    {
        self::assertFalse($this->socrates->validateId($invalidId, Country::Germany));
    }

    public function test_validation_using_ids_with_invalid_length_throw_exception(): void
    {
        $this->expectException(InvalidLengthException::class);

        $this->socrates->validateId('0123456789', Country::Germany);
    }
}
