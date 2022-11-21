<?php

namespace Reducktion\Socrates\Tests\Feature\SouthAmerica;

use Reducktion\Socrates\Constants\Country;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Exceptions\UnsupportedOperationException;
use Reducktion\Socrates\Tests\Feature\FeatureTest;

class PeruTest extends FeatureTest
{
    private array $validIds;
    private array $invalidIds;

    protected function setUp(): void
    {
        parent::setUp();

        $this->validIds = [
            '447690450',
            '858845624',
            '09189667B',
            '739184148',
            '83015889H',
            '178011460',
            '101174102',
            '43451826-7',
            '10117410-G',
            '06460698K',
        ];

        $this->invalidIds = [
            '101174103',
            '10117410H',
            '178A11460',
            '83015889B',
            '09189667V',
            '85884562D',
            '55833222D',
            '64708232D',
            '64015292J',
            '73918414W',
        ];
    }

    public function test_extract_behaviour(): void
    {
        $this->expectException(UnsupportedOperationException::class);

        $this->socrates->getCitizenDataFromId('', Country::Peru);
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->validIds as $id) {
            self::assertTrue(
                $this->socrates->validateId($id, Country::Peru),
                $id
            );
        }

        foreach ($this->invalidIds as $id) {
            self::assertFalse(
                $this->socrates->validateId($id, Country::Peru)
            );
        }

        $this->expectException(InvalidLengthException::class);

        $this->socrates->validateId('1234567', Country::Peru);
    }
}
