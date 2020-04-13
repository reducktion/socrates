<?php

namespace Reducktion\Socrates\Tests;

use Illuminate\Support\Facades\App;
use Reducktion\Socrates\Exceptions\InvalidCountryCodeException;
use Reducktion\Socrates\Exceptions\UnrecognisedCountryException;
use Reducktion\Socrates\Exceptions\UnsupportedOperationException;
use Reducktion\Socrates\Facades\Socrates;

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

    /** @test */
    public function it_uses_the_application_current_locale_if_none_is_provided(): void
    {
        App::setLocale('PT');

        $this->assertTrue(
            Socrates::validateId('11084129 8 ZX8')
        );

        $this->expectException(UnsupportedOperationException::class);

        Socrates::getCitizenDataFromId('11084129 8 ZX8');
    }
}