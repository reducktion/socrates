<?php

namespace Reducktion\Socrates\Tests\Feature;

use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Facades\Socrates;
use Reducktion\Socrates\Tests\TestCase;

class DenmarkTest extends TestCase
{
    /** @test */
    public function extract_test(): void
    {
        $citizen = Socrates::getCitizenDataFromId('251195-1448', 'DK');

        $this->assertEquals(Gender::FEMALE, $citizen->getGender());
    }

    /** @test */
    public function id_validator_test(): void
    {
        $this->assertTrue(
            Socrates::validateId('251195-1448', 'DK')
        );
    }
}