<?php

namespace Reducktion\Socrates\Tests\Feature\Europe;

use Reducktion\Socrates\Laravel\Facades\Socrates;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Exceptions\UnsupportedOperationException;
use Reducktion\Socrates\Tests\Feature\FeatureTest;

class PortugalTest extends FeatureTest
{
    private $validIds;
    private $invalidIds;

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

        $this->invalidIds = [
            '11084129 0 ZX8',
            '154A03556ZX9',
            '176CD917 4ZZ5',
            '174886000 ZX1',
            '14811175 4 ZY5',
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

        foreach ($this->invalidIds as $invalidId) {
            $this->assertFalse(
                Socrates::validateId($invalidId, 'PT')
            );
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::validateId('11084129 8 ZX', 'PT');
    }
}
