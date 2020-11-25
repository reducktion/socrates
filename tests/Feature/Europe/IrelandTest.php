<?php

namespace Reducktion\Socrates\Tests\Feature\Europe;

use Reducktion\Socrates\Laravel\Facades\Socrates;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Exceptions\UnsupportedOperationException;
use Reducktion\Socrates\Tests\Feature\FeatureTest;

class IrelandTest extends FeatureTest
{
    private $validIds;
    private $invalidIds;

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

        Socrates::getCitizenDataFromId('', 'IE');
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->validIds as $id) {
            self::assertTrue(
                Socrates::validateId($id, 'IE')
            );
        }

        foreach ($this->invalidIds as $id) {
            self::assertFalse(
                Socrates::validateId($id, 'IE')
            );
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::validateId('648295R', 'IE');
    }
}
