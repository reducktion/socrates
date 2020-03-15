<?php

namespace Reducktion\Socrates\Tests\Feature;

use Reducktion\Socrates\Facades\Socrates;

class ItalyTest extends FeatureTest
{
    private $people;
    private $invalidIds;

    public function test_extract_behaviour(): void
    {
        $this->assertTrue(true);
    }

    public function test_validation_behaviour(): void
    {
        $this->assertTrue(
//            Socrates::validateId('MRTMTT25D09F205Z', 'IT')
            Socrates::validateId('MLLSNT82P65Z404U', 'IT')
        );
    }

}