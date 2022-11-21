<?php

namespace Reducktion\Socrates\Tests\Feature\SouthAmerica;

use Reducktion\Socrates\Constants\Country;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Exceptions\UnsupportedOperationException;
use Reducktion\Socrates\Tests\Feature\FeatureTest;

class UruguayTest extends FeatureTest
{
    private array $validIds;
    private array $invalidIds;

    protected function setUp(): void
    {
        parent::setUp();

        $this->validIds = [
            '9.814.898-7',
            '99609297',
            '7.019.519.6',
            '2.797.689 6',
            '24895354',
            '40008026',
            '60125711',
            '11111111',
            '22222222',
            '52574582',
            '96072455',
            '70505244',
        ];

        $this->invalidIds = [
            '46057422',
            '90224632',
            '52631437',
            '62634608',
            '23801966',
            '27452675',
            '32311266',
            '94448560',
            '59672227',
            '28441574',
            '11111112',
        ];
    }

    public function test_extract_behaviour(): void
    {
        $this->expectException(UnsupportedOperationException::class);

        $this->socrates->getCitizenDataFromId('', Country::Uruguay);
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->validIds as $id) {
            self::assertTrue(
                $this->socrates->validateId($id, Country::Uruguay),
                $id
            );
        }

        foreach ($this->invalidIds as $id) {
            self::assertFalse(
                $this->socrates->validateId($id, Country::Uruguay)
            );
        }

        $this->expectException(InvalidLengthException::class);

        $this->socrates->validateId('1234567', Country::Uruguay);
    }
}
