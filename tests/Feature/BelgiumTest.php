<?php


namespace Reducktion\Socrates\Tests\Feature;


use PHPUnit\Framework\TestCase;
use Reducktion\Socrates\Socrates;

class BelgiumTest extends TestCase
{
    /** @test */
    public function extract_test(): void
    {
        $socrates = new Socrates();

        $citizen = $socrates->getCitizenDataFromId("93.05.18-223.61", "BE");

        $this->assertEquals("Male", $citizen->getGender());
    }

    /** @test */
    public function id_validator_test(): void
    {
        $socrates = new Socrates();

        $this->assertTrue(
          $socrates->validateId("93.05.18-223.61", "BE")
        );
    }
}