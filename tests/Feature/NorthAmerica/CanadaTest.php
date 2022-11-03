<?php

namespace Reducktion\Socrates\Tests\Feature\NorthAmerica;

use Reducktion\Socrates\Constants\Country;
use Reducktion\Socrates\Tests\Feature\FeatureTest;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Exceptions\UnsupportedOperationException;

class CanadaTest extends FeatureTest
{
    private array $validIds;
    private array $invalidIds;

    protected function setUp(): void
    {
        parent::setUp();

        $this->validIds = [
            '046 454 286',
            '671 143 899',
            '002 371 920',
            '501 343 719',
            '912 046 737',
        ];

        $this->invalidIds = [
            '512 917 638',
            '322 710 094',
            '761 999 512',
            '061 003 528',
            '654 789 093',
        ];
    }

    public function test_extract_behaviour(): void
    {
        $this->expectException(UnsupportedOperationException::class);

        $this->socrates->getCitizenDataFromId('85 712 123', Country::Canada);
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->validIds as $id) {
            self::assertTrue(
                $this->socrates->validateId($id, Country::Canada)
            );
        }

        foreach ($this->invalidIds as $invalidId) {
            self::assertFalse(
                $this->socrates->validateId($invalidId, Country::Canada)
            );
        }

        $this->expectException(InvalidLengthException::class);

        $this->socrates->validateId('781 211 2231', Country::Canada);
    }
}
