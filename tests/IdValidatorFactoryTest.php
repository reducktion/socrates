<?php

namespace Reducktion\Socrates\Tests;

use Reducktion\Socrates\Contracts\IdValidator;
use Reducktion\Socrates\Core\IdValidatorFactory;
use RuntimeException;

class IdValidatorFactoryTest extends TestCase
{
    /** @test */
    public function it_gets_validator(): void
    {
        $validator = IdValidatorFactory::getValidator('FI');
        $this->assertInstanceOf(IdValidator::class, $validator);
    }

    /** @test */
    public function it_throws_for_unknown_country(): void
    {
        $this->expectException(RuntimeException::class);
        IdValidatorFactory::getValidator('XX');
    }
}
