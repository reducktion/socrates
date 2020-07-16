<?php

namespace Reducktion\Socrates\Tests\Feature\Europe;

use Reducktion\Socrates\Laravel\Facades\Socrates;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Exceptions\UnsupportedOperationException;
use Reducktion\Socrates\Tests\Feature\FeatureTest;

class MoldovaTest extends FeatureTest
{
    private $validIds;
    private $invalidIds;

    protected function setUp(): void
    {
        parent::setUp();

        $this->validIds = [
            '2012002003163',
            '2012002001963',
            '2011002007083',
            '2013002000066',
        ];

        $this->invalidIds = [
            '012345689123A',
            '02A4567891239',
            '7012002003163',
            '0011002007083'
        ];
    }

    public function test_extract_behaviour(): void
    {
        $this->expectException(UnsupportedOperationException::class);

        Socrates::getCitizenDataFromId('', 'MD');
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->validIds as $id) {
            $this->assertTrue(
                Socrates::validateId($id, 'MD')
            );
        }

        foreach ($this->invalidIds as $id) {
            $this->assertFalse(
                Socrates::validateId($id, 'MD')
            );
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::validateId('648295', 'MD');
    }
}
