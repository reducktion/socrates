<?php

namespace Reducktion\Socrates\Tests\Feature\Europe;

use Reducktion\Socrates\Constants\Country;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Exceptions\UnsupportedOperationException;
use Reducktion\Socrates\Tests\Feature\FeatureTest;

class TurkeyTest extends FeatureTest
{
    private array $validIds;
    private array $invalidIds;

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

        $this->socrates->getCitizenDataFromId('69218938062', Country::Turkey);
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->validIds as $id) {
            self::assertTrue(
                $this->socrates->validateId($id, Country::Turkey)
            );
        }

        foreach ($this->invalidIds as $invalidId) {
            self::assertFalse(
                $this->socrates->validateId($invalidId, Country::Turkey)
            );
        }

        $this->expectException(InvalidLengthException::class);

        $this->socrates->validateId('1621543419643', Country::Turkey);
    }
}
