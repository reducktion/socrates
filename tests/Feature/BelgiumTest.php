<?php

namespace Reducktion\Socrates\Tests\Feature;

use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Facades\Socrates;

class BelgiumTest extends FeatureTest
{
    public function test_extract_behaviour(): void
    {
        $citizen = Socrates::getCitizenDataFromId('93.05.18-223.61', 'BE');

        $this->assertEquals(Gender::MALE, $citizen->getGender());
    }

    public function test_validation_behaviour(): void
    {
        $this->assertTrue(
            Socrates::validateId('93.05.18-223.61', 'BE')
        );
    }
}