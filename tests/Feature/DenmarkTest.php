<?php


namespace Reducktion\Socrates\Tests\Feature;


use Orchestra\Testbench\TestCase;
use Reducktion\Socrates\Socrates;

class DenmarkTest extends TestCase
{
    /** @test */
    public function extract_test(): void
    {
        $socrates = new Socrates();

        $citizen = $socrates->getCitizenDataFromId("251195-1448", "DK");

        $this->assertEquals("Female", $citizen->getGender());
    }

    /** @test */
    public function id_validator_test(): void
    {
        $socrates = new Socrates();

        $this->assertTrue(
          $socrates->validateId("251195-1448", "DK")
        );
    }
}