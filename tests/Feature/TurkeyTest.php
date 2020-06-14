<?php

namespace Reducktion\Socrates\Tests\Feature;

use Reducktion\Socrates\Laravel\Facades\Socrates;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Exceptions\UnsupportedOperationException;

class TurkeyTest extends FeatureTest
{
    private $validIds;
    private $invalidIds;

    protected function setUp(): void
    {
        parent::setUp();

        $this->validIds = [
            '62726927764',
            '59815159946',
            '16215434196',
            '20329122900',
            '61614559018',
        ];

        $this->invalidIds = [
            '16675430196',
            '06675430196',
            '16200000196',
            '20333322900',
            '63424559018',
        ];
    }

    public function test_extract_behaviour(): void
    {
        $this->expectException(UnsupportedOperationException::class);

        Socrates::getCitizenDataFromId('69218938062', 'TR');
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->validIds as $id) {
            $this->assertTrue(
                Socrates::validateId($id, 'TR')
            );
        }

        foreach ($this->invalidIds as $invalidId) {
            $this->assertFalse(
                Socrates::validateId($invalidId, 'TR')
            );
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::validateId('1621543419643', 'TR');
    }
}
