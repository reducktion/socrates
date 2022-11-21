<?php

namespace Reducktion\Socrates\Tests\Feature\SouthAmerica;

use Reducktion\Socrates\Constants\Country;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Exceptions\UnsupportedOperationException;
use Reducktion\Socrates\Tests\Feature\FeatureTest;

class ChileTest extends FeatureTest
{
    private array $validIds;
    private array $invalidIds;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validIds = [
            '19132031-K',
            '5587600-2',
            '22269631-3',
            '11881570-K',
            '12067530-3',
            '14674646-2',
            '14928530-K',
            '22931750-4',
            '5258230-K',
            '11077954-2',
            '30.686.957-4',
        ];

        $this->invalidIds = [
            '19132031-k', //number valid but lower "k"
            '11111112-9',
            '55185352-8',
            '19947727-K',
            '12345689-2',
            '11111111-L',
            '22931750-K',
        ];
    }

    public function test_extract_behaviour(): void
    {
        $this->expectException(UnsupportedOperationException::class);

        $this->socrates->getCitizenDataFromId('', Country::Chile);
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->validIds as $id) {
            self::assertTrue(
                $this->socrates->validateId($id, Country::Chile),
                $id
            );
        }

        foreach ($this->invalidIds as $id) {
            self::assertFalse(
                $this->socrates->validateId($id, Country::Chile)
            );
        }

        $this->expectException(InvalidLengthException::class);

        $this->socrates->validateId('1234567', Country::Chile);
    }
}
