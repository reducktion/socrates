<?php

namespace Reducktion\Socrates\Tests\Feature\Europe;

use Reducktion\Socrates\Constants\Country;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Exceptions\UnsupportedOperationException;
use Reducktion\Socrates\Tests\Feature\FeatureTest;

class GreeceTest extends FeatureTest
{
    private array $validIds;
    private array $invalidIds;

    protected function setUp(): void
    {
        parent::setUp();

        $this->validIds = [
            'ΔΞ-891728',
            'ΚΦ-012734',
            'ΖΜ-431981',
            'ΒΠ-018621',
            'ΩΗ-877612',
            'ΑΜ-811664',
        ];

        $this->invalidIds = [
            'Δx-091003',
            'Ω-1213312',
            'ΒΖΜ-98912',
            'ΧΘ-543971',
            'ΛΨ-087125',
            'ΑΜ-81A13I',
            '12-123123',
        ];
    }

    public function test_extract_behaviour(): void
    {
        $this->expectException(UnsupportedOperationException::class);

        $this->socrates->getCitizenDataFromId('ΔΞ-891728', Country::Greece);
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->validIds as $id) {
            self::assertTrue(
                $this->socrates->validateId($id, Country::Greece)
            );
        }

        foreach ($this->invalidIds as $invalidId) {
            self::assertFalse(
                $this->socrates->validateId($invalidId, Country::Greece)
            );
        }

        $this->expectException(InvalidLengthException::class);

        $this->socrates->validateId('ΔΞ-89172', Country::Greece);
    }
}
