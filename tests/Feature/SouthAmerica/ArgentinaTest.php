<?php

namespace Reducktion\Socrates\Tests\Feature\SouthAmerica;

use Reducktion\Socrates\Constants\Country;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Exceptions\UnsupportedOperationException;
use Reducktion\Socrates\Tests\Feature\FeatureTest;

class ArgentinaTest extends FeatureTest
{
    private array $validIds;
    private array $invalidIds;

    protected function setUp(): void
    {
        parent::setUp();

        $this->validIds = [
            '20319731467',
            '23129006544',
            '20385823631',
            '20123456786',
            '33711146259',
            '30716762145',
            '30629357552',
            '27061822238',
            '20028314788',
            '23010852064',
            '27083496970',
        ];

        $this->invalidIds = [
            '42319731467',
            '20999999997',
            '20349735981',
            '20123456785',
            '30010852064',
        ];
    }

    public function test_extract_behaviour(): void
    {
        $this->expectException(UnsupportedOperationException::class);

        $this->socrates->getCitizenDataFromId('', Country::Argentina);
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->validIds as $id) {
            self::assertTrue(
                $this->socrates->validateId($id, Country::Argentina),
                $id
            );
        }

        foreach ($this->invalidIds as $id) {
            self::assertFalse(
                $this->socrates->validateId($id, Country::Argentina)
            );
        }

        $this->expectException(InvalidLengthException::class);

        $this->socrates->validateId('123456', Country::Argentina);
    }
}
