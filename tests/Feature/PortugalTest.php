<?php

namespace Reducktion\Socrates\Tests\Feature;

use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Exceptions\UnsupportedOperationException;
use Reducktion\Socrates\Facades\Socrates;

class PortugalTest extends FeatureTest
{
    private $validIds;

    protected function setUp(): void
    {
        parent::setUp();

        $this->validIds = [
            '11084129 8 ZX8',
            '154203556ZX9',
            '17653917 4ZZ5',
            '174886721 ZX1',
            '14898475 4 ZY5',
        ];
    }

    public function test_extract_behaviour(): void
    {
        $this->expectException(UnsupportedOperationException::class);

        Socrates::getCitizenDataFromId('145565258ZZY', 'PT');
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->validIds as $id) {
            $this->assertTrue(
                Socrates::validateId($id, 'PT')
            );
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::validateId('11084129 8 ZX', 'PT');

        $this->assertFalse(
            Socrates::validateId('14897475 4 ZY5', 'PT')
        );
    }
}