<?php

namespace Reducktion\Socrates\Tests\Feature\NorthAmerica;

use Reducktion\Socrates\Constants\Country;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Exceptions\UnsupportedOperationException;
use Reducktion\Socrates\Tests\Feature\FeatureTest;

class UnitedStatesTest extends FeatureTest
{
    private array $validIds;
    private array $invalidIds;

    protected function setUp(): void
    {
        parent::setUp();

        $this->validIds = [
            '167-38-1265',
            '536-22-8726',
            '536-22-5232',
            '574-22-7664',
            '671-26-9121'
        ];

        $this->invalidIds = [
            '078-05-1120',
            '219-09-9999',
            '457-55-5462',
            '666-91-8271',
            '000-12-7652',
            '167-00-6321',
            '167-11-0000',
            '981-76-1521',
        ];
    }

    public function test_extract_behaviour(): void
    {
        $this->expectException(UnsupportedOperationException::class);

        $this->socrates->getCitizenDataFromId('145565258ZZY', Country::UnitedStates);
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->validIds as $id) {
            self::assertTrue(
                $this->socrates->validateId($id, Country::UnitedStates)
            );
        }

        foreach ($this->invalidIds as $invalidId) {
            self::assertFalse(
                $this->socrates->validateId($invalidId, Country::UnitedStates)
            );
        }

        $this->expectException(InvalidLengthException::class);

        $this->socrates->validateId('11084129 8 ZX', Country::UnitedStates);
    }
}
