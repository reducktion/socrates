<?php

namespace Reducktion\Socrates\Tests\Feature\Europe;

use Reducktion\Socrates\Constants\Country;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Exceptions\UnsupportedOperationException;
use Reducktion\Socrates\Tests\Feature\FeatureTest;

class SwitzerlandTest extends FeatureTest
{
    private array $validIds;
    private array $invalidIds;

    protected function setUp(): void
    {
        parent::setUp();

        $this->validIds = [
            '756.2765.8310.82',
            '756.9523.9278.34',
            '756.6608.0959.83',
            '7562514028206',
            '7568193378885',
        ];

        $this->invalidIds = [
            '7668193378885',
            '046.6620.1959.12',
            '776.6608.0959.83',
            '75.62000008206',
            '7568.193378002',
        ];
    }

    public function test_extract_behaviour(): void
    {
        $this->expectException(UnsupportedOperationException::class);

        $this->socrates->getCitizenDataFromId('756.2765.8310.82', Country::Switzerland);
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->validIds as $id) {
            self::assertTrue(
                $this->socrates->validateId($id, Country::Switzerland)
            );
        }

        foreach ($this->invalidIds as $invalidId) {
            self::assertFalse(
                $this->socrates->validateId($invalidId, Country::Switzerland)
            );
        }

        $this->expectException(InvalidLengthException::class);

        $this->socrates->validateId('756.608.0959.83', Country::Switzerland);

        $this->expectException(InvalidLengthException::class);

        $this->socrates->validateId('756193378885', Country::Switzerland);
    }
}
