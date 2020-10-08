<?php

namespace Reducktion\Socrates\Tests\Feature;

use Reducktion\Socrates\Laravel\Facades\Socrates;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Exceptions\UnsupportedOperationException;

class MexicoTest extends FeatureTest
{
    private $validIds;
    private $invalidIds;
    private $MEXICO_CODE = 'MX';

    protected function setUp(): void
    {
        parent::setUp();

        $this->validIds = [
            'HEGG560427MVZRRL04',
            'BOXW310820HNERXN09',
            'MAAR790213HMNRLF03',
        ];

        $this->invalidIds = [
            '1234790213HMNRLF03',
            'BOXW310820HNERXN08',
        ];
    }

    public function test_extract_behaviour(): void
    {
        $this->expectException(UnsupportedOperationException::class);

        Socrates::getCitizenDataFromId('69218938062', $this->MEXICO_CODE);
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->validIds as $id) {
            $this->assertTrue(
                Socrates::validateId($id, $this->MEXICO_CODE)
            );
        }

        foreach ($this->invalidIds as $invalidId) {
            $this->assertFalse(
                Socrates::validateId($invalidId, $this->MEXICO_CODE)
            );
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::validateId('1621543419643', $this->MEXICO_CODE);
    }
}
