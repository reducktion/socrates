<?php

namespace Reducktion\Socrates\Tests\Feature\Europe;

use PHPUnit\Framework\Attributes\DataProvider;
use Reducktion\Socrates\Constants\Country;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Exceptions\UnsupportedOperationException;
use Reducktion\Socrates\Tests\Feature\FeatureTestCase;

class GreeceTest extends FeatureTestCase
{
    public static function extractionDataProvider(): array
    {
        return [
            'unknown' => [
                'person' => [
                    'id' => 'ΔΞ-891728',
                ],
            ],
        ];
    }

    public static function peopleDataProvider(): array
    {
        return [
            'unknown1' => [
                'person' => [
                    'id' => 'ΔΞ-891728',
                ],
            ],
            'unknown2' => [
                'person' => [
                    'id' => 'ΚΦ-012734',
                ],
            ],
            'unknown3' => [
                'person' => [
                    'id' => 'ΖΜ-431981',
                ],
            ],
            'unknown4' => [
                'person' => [
                    'id' => 'ΒΠ-018621',
                ],
            ],
            'unknown5' => [
                'person' => [
                    'id' => 'ΩΗ-877612',
                ],
            ],
            'unknown6' => [
                'person' => [
                    'id' => 'ΑΜ-811664',
                ],
            ],
        ];
    }

    public static function invalidIdsDataProvider(): array
    {
        return [
            ['Δx-091003'],
            ['Ω-1213312'],
            ['ΒΖΜ-98912'],
            ['ΧΘ-543971'],
            ['ΛΨ-087125'],
            ['ΑΜ-81A13I'],
            ['12-123123'],
        ];
    }

    #[DataProvider('extractionDataProvider')]
    public function test_extract_behaviour(array $person): void
    {
        $this->expectException(UnsupportedOperationException::class);

        $this->socrates->getCitizenDataFromId($person['id'], Country::Greece);
    }

    #[DataProvider('peopleDataProvider')]
    public function test_validation_with_valid_ids_passes(array $person): void
    {
        self::assertTrue($this->socrates->validateId($person['id'], Country::Greece));
    }

    #[DataProvider('invalidIdsDataProvider')]
    public function test_validation_with_invalid_ids_fails(string $invalidId): void
    {
        self::assertFalse($this->socrates->validateId($invalidId, Country::Greece));
    }

    public function test_validation_using_ids_with_invalid_length_throw_exception(): void
    {
        $this->expectException(InvalidLengthException::class);

        $this->socrates->validateId('ΔΞ-89172', Country::Greece);
    }
}
