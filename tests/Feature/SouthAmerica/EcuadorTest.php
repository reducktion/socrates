<?php

namespace Reducktion\Socrates\Tests\Feature\SouthAmerica;

use Reducktion\Socrates\Constants\Country;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Exceptions\UnsupportedOperationException;
use Reducktion\Socrates\Tests\Feature\FeatureTest;

class EcuadorTest extends FeatureTest
{
    private array $validIds;
    private array $invalidIds;

    protected function setUp(): void
    {
        parent::setUp();

        $this->validIds = [
            '0926687856',
            '1159944279',
            '0350442026',
            '1930773021',
            '2010123061',
            '1710034065',
        ];

        $this->invalidIds = [
            '1234567890',
            '2510034065',
            '1770034065',
            '1570111029',
            '0126987368',
        ];
    }

    public function test_extract_behaviour(): void
    {
        $this->expectException(UnsupportedOperationException::class);

        $this->socrates->getCitizenDataFromId('', Country::Ecuador);
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->validIds as $id) {
            self::assertTrue(
                $this->socrates->validateId($id, Country::Ecuador),
                $id
            );
        }

        foreach ($this->invalidIds as $id) {
            self::assertFalse(
                $this->socrates->validateId($id, Country::Ecuador)
            );
        }

        $this->expectException(InvalidLengthException::class);

        $this->socrates->validateId('123456789', Country::Ecuador);
    }
}
