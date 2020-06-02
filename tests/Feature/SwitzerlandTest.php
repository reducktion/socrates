<?php

namespace Reducktion\Socrates\Tests\Feature;

use Reducktion\Socrates\Laravel\Facades\Socrates;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Exceptions\UnsupportedOperationException;

class SwitzerlandTest extends FeatureTest
{
    private $validIds;

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
    }

    public function test_extract_behaviour(): void
    {
        $this->expectException(UnsupportedOperationException::class);

        Socrates::getCitizenDataFromId('756.2765.8310.82', 'CH');
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->validIds as $id) {
            $this->assertTrue(
                Socrates::validateId($id, 'CH')
            );
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::validateId('756.608.0959.83', 'CH');

        $this->expectException(InvalidLengthException::class);

        Socrates::validateId('756193378885', 'CH');

        $this->assertFalse(
            Socrates::validateId('7668193378885', 'CH')
        );

        $this->assertFalse(
            Socrates::validateId('046.6620.1959.12', 'CH')
        );
    }
}
