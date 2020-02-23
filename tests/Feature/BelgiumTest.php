<?php

namespace Reducktion\Socrates\Tests\Feature;

use Orchestra\Testbench\TestCase;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Facades\Socrates;

class BelgiumTest extends TestCase
{
    /** @test */
    public function extract_test(): void
    {
//        $citizen = Socrates::getCitizenDataFromId("93.05.18-223.61", "BE");
//
//        $this->assertEquals(Gender::MALE, $citizen->getGender());
    }

    /** @test */
    public function id_validator_test(): void
    {
//        $this->assertTrue(
//            Socrates::validateId("93.05.18-223.61", "BE")
//        );
    }
}