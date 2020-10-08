<?php

namespace Reducktion\Socrates\Tests\Feature\LatinAmerica;

use Reducktion\Socrates\Laravel\Facades\Socrates;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Exceptions\UnsupportedOperationException;
use Reducktion\Socrates\Tests\Feature\FeatureTest;

class ChileTest extends FeatureTest
{
    private $validIds;
    private $invalidIds;

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

        Socrates::getCitizenDataFromId('', 'CL');
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->validIds as $id) {
            $this->assertTrue(
                Socrates::validateId($id, 'CL'),
                $id
            );
        }

        foreach ($this->invalidIds as $id) {
            $this->assertFalse(
                Socrates::validateId($id, 'CL')
            );
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::validateId('1234567', 'CL');
    }
}
