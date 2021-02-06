<?php

namespace Reducktion\Socrates\Tests\Feature\SouthAmerica;

use Reducktion\Socrates\Laravel\Facades\Socrates;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Exceptions\UnsupportedOperationException;
use Reducktion\Socrates\Tests\Feature\FeatureTest;

class EcuadorTest extends FeatureTest
{
    private $validIds;
    private $invalidIds;

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

        Socrates::getCitizenDataFromId('', 'EC');
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->validIds as $id) {
            self::assertTrue(
                Socrates::validateId($id, 'EC'),
                $id
            );
        }

        foreach ($this->invalidIds as $id) {
            self::assertFalse(
                Socrates::validateId($id, 'EC')
            );
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::validateId('123456789', 'EC');
    }
}
