<?php

namespace Reducktion\Socrates\Tests;

use PHPUnit\Framework\TestCase;
use Reducktion\Socrates\Constants\Country;
use Reducktion\Socrates\Contracts\IdValidator;
use Reducktion\Socrates\Core\IdValidatorFactory;

class IdValidatorFactoryTest extends TestCase
{
    /** @test */
    public function it_gets_validator(): void
    {
        $validator = IdValidatorFactory::getValidator(Country::Finland);
        $this->assertInstanceOf(IdValidator::class, $validator);
    }
}
