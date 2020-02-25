<?php

namespace Reducktion\Socrates\Core;

use Reducktion\Socrates\Contracts\CitizenInformationExtractor;
use RuntimeException;

abstract class CitizenInformationExtractorFactory
{
    public static function getExtractor(string $countryCode): CitizenInformationExtractor {

        $extractors = config('socrates.extractors');

        if (! isset($extractors[$countryCode])) {
            throw new RuntimeException("Unknown or unsupported country code '$countryCode'");
        }

        return new $extractors[$countryCode];
    }
}
