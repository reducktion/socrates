<?php

namespace Reducktion\Socrates\Tests\Feature\LatinAmerica;

use Reducktion\Socrates\Laravel\Facades\Socrates;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Exceptions\UnsupportedOperationException;
use Reducktion\Socrates\Tests\Feature\FeatureTest;

class BrazilTest extends FeatureTest
{
    private $validIds;
    private $invalidIds;

    protected function setUp(): void
    {
        parent::setUp();

        // one per state
        $this->validIds = [
            '14441676263',
            '62363568400',
            '92205820230',
            '88958056231',
            '90701066555',
            '31098035348',
            '54271183148',
            '03860881795',
            '15777379117',
            '46959616360',
            '51861041675',
            '35823686102',
            '26319324120',
            '81036850463',
            '17188856443',
            '16556182451',
            '13369586347',
            '19319810940',
            '41120495792',
            '79950524482',
            '44667914068',
            '41947527240',
            '23554835234',
            '04008125922',
            '37025634581',
            '26363102820',
            '17758534112',
        ];

        $this->invalidIds = [
            '23294954040',
            '92940752020',
            '34612661005',
            '46943217073',
            '13109540000',
        ];
    }

    public function test_extract_behaviour(): void
    {
        $this->expectException(UnsupportedOperationException::class);

        Socrates::getCitizenDataFromId('', 'BR');
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->validIds as $id) {
            $this->assertTrue(
                Socrates::validateId($id, 'BR'),
                $id
            );
        }

        foreach ($this->invalidIds as $id) {
            $this->assertFalse(
                Socrates::validateId($id, 'BR')
            );
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::validateId('648295', 'BR');
    }
}
