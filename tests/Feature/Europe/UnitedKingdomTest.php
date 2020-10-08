<?php

namespace Reducktion\Socrates\Tests\Feature\Europe;

use Reducktion\Socrates\Laravel\Facades\Socrates;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Exceptions\UnsupportedOperationException;
use Reducktion\Socrates\Tests\Feature\FeatureTest;

class UnitedKingdomTest extends FeatureTest
{
    private $validIds;
    private $invalidIds;

    protected function setUp(): void
    {
        parent::setUp();

        $this->validIds = [
            'AA370773A',
            'KY63 1234 D',
            'ec92 2870D',
            'GG 79 89 59 B',
            'Bw 99 04 28 b',
            'LS93 96 41 D',
            'cR 93 8036 c',
            'YM538647B',
        ];

        $this->invalidIds = [
            'AA 37 07 73 F',
            '3392 2870 9',
            'GG 79 gj 59 B',
            'FO 99 04 28 C',
            'GB 79 89 59 B',
            'NK 79 89 59 B',
            'TN 79 89 59 B',
            'ZZ 79 89 59 B',
            'KY 63 1234 W',
            'UY 63 1234 W',
            'AU 63 1234 B',
        ];
    }

    public function test_extract_behaviour(): void
    {
        $this->expectException(UnsupportedOperationException::class);

        Socrates::getCitizenDataFromId('AA370773A', 'GB');
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->validIds as $id) {
            $this->assertTrue(
                Socrates::validateId($id, 'GB')
            );
        }

        foreach ($this->invalidIds as $invalidId) {
            $this->assertFalse(
                Socrates::validateId($invalidId, 'GB')
            );
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::validateId('AA370773', 'GB');
    }
}
