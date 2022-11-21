<?php

namespace Reducktion\Socrates\Tests\Feature\Europe;

use Reducktion\Socrates\Constants\Country;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Exceptions\UnsupportedOperationException;
use Reducktion\Socrates\Tests\Feature\FeatureTest;

class SpainTest extends FeatureTest
{
    private array $validIds;
    private array $invalidIds;

    protected function setUp(): void
    {
        parent::setUp();

        $this->validIds = [
            '84345642L',
            'Y3338121F',
            '40298386V',
            'Y0597591L',
            '09730915Y',
        ];

        $this->invalidIds = [
            '05756786M',
            'YY597522L',
            '4020X069V',
            'XX-597591L',
            '09730215Y',
        ];
    }

    public function test_extract_behaviour(): void
    {
        $this->expectException(UnsupportedOperationException::class);

        $this->socrates->getCitizenDataFromId('X9464186P', Country::Spain);
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->validIds as $id) {
            self::assertTrue(
                $this->socrates->validateId($id, Country::Spain)
            );
        }

        foreach ($this->invalidIds as $invalidId) {
            self::assertFalse(
                $this->socrates->validateId($invalidId, Country::Spain)
            );
        }

        $this->expectException(InvalidLengthException::class);

        $this->socrates->validateId('05751086', Country::Spain);
    }
}
