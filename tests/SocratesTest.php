<?php

namespace Reducktion\Socrates\Tests;

use Illuminate\Support\Facades\App;
use Reducktion\Socrates\Exceptions\InvalidCountryCodeException;
use Reducktion\Socrates\Exceptions\UnrecognisedCountryException;
use Reducktion\Socrates\Exceptions\UnsupportedOperationException;
use Reducktion\Socrates\Laravel\Facades\Socrates;

class SocratesTest extends TestCase
{
    /** @test */
    public function it_throws_an_exception_if_a_country_code_does_not_exist(): void
    {
        $this->expectException(UnrecognisedCountryException::class);

        Socrates::validateId('123123123', 'ZZ');
    }

    /** @test */
    public function it_throws_an_exception_if_a_country_code_is_in_the_wrong_format(): void
    {
        $this->expectException(InvalidCountryCodeException::class);

        Socrates::validateId('123123123', 'ZZZ');
    }
}
