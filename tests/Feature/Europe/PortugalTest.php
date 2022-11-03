<?php

namespace Reducktion\Socrates\Tests\Feature\Europe;

use Reducktion\Socrates\Constants\Country;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Exceptions\UnsupportedOperationException;
use Reducktion\Socrates\Tests\Feature\FeatureTest;

class PortugalTest extends FeatureTest
{
    private array $validIds;
    private array $invalidIds;

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

        $this->socrates->getCitizenDataFromId('145565258ZZY', Country::Portugal);
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->validIds as $id) {
            self::assertTrue(
                $this->socrates->validateId($id, Country::Portugal)
            );
        }

        foreach ($this->invalidIds as $invalidId) {
            self::assertFalse(
                $this->socrates->validateId($invalidId, Country::Portugal)
            );
        }

        $this->expectException(InvalidLengthException::class);

        $this->socrates->validateId('11084129 8 ZX', Country::Portugal);
    }
}
