<?php

namespace Reducktion\Socrates\Core;

use Reducktion\Socrates\Constants\Country;
use RuntimeException;
use Reducktion\Socrates\Config\Countries;
use Reducktion\Socrates\Contracts\CitizenInformationExtractor;

abstract class CitizenInformationExtractorFactory
{
    public static function getExtractor(Country $country): CitizenInformationExtractor
    {
        if (! isset(Countries::$extractors[$country->value])) {
            throw new RuntimeException("Unknown or unsupported country code '$country->value'");
        }

        return new Countries::$extractors[$country->value]();
    }
}
