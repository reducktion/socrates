<?php

namespace Reducktion\Socrates\Tests\Feature\NorthAmerica;

use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Exceptions\UnsupportedOperationException;
use Reducktion\Socrates\Laravel\Facades\Socrates;
use Reducktion\Socrates\Tests\Feature\FeatureTest;

class CanadaTest extends FeatureTest
{
    private $validIds;
    private $invalidIds;

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

        Socrates::getCitizenDataFromId('85 712 123', 'CA');
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->validIds as $id) {
            $this->assertTrue(
                Socrates::validateId($id, 'CA')
            );
        }

        foreach ($this->invalidIds as $invalidId) {
            $this->assertFalse(
                Socrates::validateId($invalidId, 'CA')
            );
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::validateId('781 211 2231', 'CA');
    }

}
