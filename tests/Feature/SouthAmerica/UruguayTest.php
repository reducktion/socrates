<?php

namespace Reducktion\Socrates\Tests\Feature\SouthAmerica;

use Reducktion\Socrates\Laravel\Facades\Socrates;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Exceptions\UnsupportedOperationException;
use Reducktion\Socrates\Tests\Feature\FeatureTest;

class UruguayTest extends FeatureTest
{
    private $validIds;
    private $invalidIds;

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

        Socrates::getCitizenDataFromId('', 'UY');
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->validIds as $id) {
            $this->assertTrue(
                Socrates::validateId($id, 'UY'),
                $id
            );
        }

        foreach ($this->invalidIds as $id) {
            $this->assertFalse(
                Socrates::validateId($id, 'UY')
            );
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::validateId('1234567', 'UY');
    }
}
