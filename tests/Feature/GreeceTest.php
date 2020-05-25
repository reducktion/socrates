<?php

namespace Reducktion\Socrates\Tests\Feature;

use Reducktion\Socrates\Facades\Socrates;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Exceptions\UnsupportedOperationException;

class GreeceTest extends FeatureTest
{
    private $validIds;
    private $invalidIds;

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
            'ΔΞ-89172',
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

        Socrates::getCitizenDataFromId('ΔΞ-891728', 'GR');
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->validIds as $id) {
            $this->assertTrue(
                Socrates::validateId($id, 'GR')
            );
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::validateId('ΔΞ-89172', 'GR');

        foreach ($this->invalidIds as $invalidId) {
            $this->assertFalse(
                Socrates::validateId($invalidId, 'GR')
            );
        }
    }

}