<?php

namespace Reducktion\Socrates\Tests\Feature\Europe;

use Reducktion\Socrates\Constants\Country;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Exceptions\UnsupportedOperationException;
use Reducktion\Socrates\Tests\Feature\FeatureTest;

class NetherlandsTest extends FeatureTest
{
    private array $validIds;
    private array $invalidIds;

    protected function setUp(): void
    {
        parent::setUp();

        $this->validIds = [
            '271138488',
            '193006248',
            '398061300',
            '719102091',
            '692676491',
        ];

        $this->invalidIds = [
            '692676411',
            '918271871',
            '908786123',
            '123128764',
            '817187288',
        ];
    }

    public function test_extract_behaviour(): void
    {
        $this->expectException(UnsupportedOperationException::class);

        $this->socrates->getCitizenDataFromId('', Country::Netherlands);
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->validIds as $id) {
            self::assertTrue(
                $this->socrates->validateId($id, Country::Netherlands)
            );
        }

        foreach ($this->invalidIds as $id) {
            self::assertFalse(
                $this->socrates->validateId($id, Country::Netherlands)
            );
        }

        $this->expectException(InvalidLengthException::class);

        $this->socrates->validateId('692676', Country::Netherlands);
    }
}
