<?php

namespace Reducktion\Socrates\Tests;

use Illuminate\Support\Facades\App;
use Reducktion\Socrates\Exceptions\InvalidCountryCodeException;
use Reducktion\Socrates\Exceptions\UnrecognisedCountryException;
use Reducktion\Socrates\Exceptions\UnsupportedOperationException;
use Reducktion\Socrates\Facades\Socrates;

class SocratesTest extends TestCase
{
    public function test_non_existent_countries(): void
    {
        $this->expectException(UnrecognisedCountryException::class);

        Socrates::validateId('123123123', 'ZZ');
    }

    public function test_wrong_country_code_format(): void
    {
        $this->expectException(InvalidCountryCodeException::class);

        Socrates::validateId('123123123', 'ZZZ');
    }

    public function test_unsupported_extractor(): void
    {
        $this->expectException(UnsupportedOperationException::class);

        Socrates::getCitizenDataFromId('123123123', 'ZW');
    }

    public function test_automatic_locale(): void
    {
        App::setLocale('DK');

        $this->assertTrue(
            Socrates::validateId('090792-1395')
        );
    }
}