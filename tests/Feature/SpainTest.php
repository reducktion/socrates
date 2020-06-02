<?php

namespace Reducktion\Socrates\Tests\Feature;

use Reducktion\Socrates\Laravel\Facades\Socrates;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Exceptions\UnsupportedOperationException;

class SpainTest extends FeatureTest
{
    private $validIds;

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
    }

    public function test_extract_behaviour(): void
    {
        $this->expectException(UnsupportedOperationException::class);

        Socrates::getCitizenDataFromId('X9464186P', 'ES');
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->validIds as $id) {
            $this->assertTrue(
                Socrates::validateId($id, 'ES')
            );
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::validateId('05751086', 'ES');

        $this->assertFalse(
            Socrates::validateId('05756786M', 'ES')
        );
    }
}
