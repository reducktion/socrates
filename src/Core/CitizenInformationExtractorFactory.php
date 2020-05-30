<?php

namespace Reducktion\Socrates\Core;

use RuntimeException;
use Reducktion\Socrates\Config\Countries;
use Reducktion\Socrates\Contracts\CitizenInformationExtractor;

abstract class CitizenInformationExtractorFactory
{
    public static function getExtractor(string $countryCode): CitizenInformationExtractor
    {

        if (! isset(Countries::$extractors[$countryCode])) {
            throw new RuntimeException("Unknown or unsupported country code '$countryCode'");
        }

        return new Countries::$extractors[$countryCode]();
    }
}
