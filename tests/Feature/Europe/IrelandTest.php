<?php

namespace Reducktion\Socrates\Tests\Feature\Europe;

use Reducktion\Socrates\Constants\Country;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Exceptions\UnsupportedOperationException;
use Reducktion\Socrates\Tests\Feature\FeatureTest;

class IrelandTest extends FeatureTest
{
    private array $validIds;
    private array $invalidIds;

    protected function setUp(): void
    {
        parent::setUp();

        $this->validIds = [
            '6312240M',
            '8337253F',
            '1234567FA',
            '3668202SH',
            '0806725GQ',
        ];

        $this->invalidIds = [
            '1827182TT',
            '1293801JJ',
            '1976122RV',
            '8192817UY',
            '1238391IP',
        ];
    }

    public function test_extract_behaviour(): void
    {
        $this->expectException(UnsupportedOperationException::class);

        $this->socrates->getCitizenDataFromId('', Country::Ireland);
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->validIds as $id) {
            self::assertTrue(
                $this->socrates->validateId($id, Country::Ireland)
            );
        }

        foreach ($this->invalidIds as $id) {
            self::assertFalse(
                $this->socrates->validateId($id, Country::Ireland)
            );
        }

        $this->expectException(InvalidLengthException::class);

        $this->socrates->validateId('648295R', Country::Ireland);
    }
}
