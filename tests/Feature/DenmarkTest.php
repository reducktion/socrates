<?php

namespace Reducktion\Socrates\Tests\Feature;

use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Facades\Socrates;

class DenmarkTest extends FeatureTest
{
    public function test_extract_behaviour(): void
    {
        $citizen = Socrates::getCitizenDataFromId('251195-1448', 'DK');

        $this->assertEquals(Gender::FEMALE, $citizen->getGender());
    }

    public function test_validation_behaviour(): void
    {
        $this->assertTrue(
            Socrates::validateId('251195-1448', 'DK')
        );
    }
}