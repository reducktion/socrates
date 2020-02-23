<?php

namespace Reducktion\Socrates\Core;

use Reducktion\Socrates\Contracts\CitizenInformationExtractor;
use RuntimeException;

abstract class CitizenInformationExtractorFactory
{
    public static function getExtractor(string $countryCode): CitizenInformationExtractor {

        $extractors = config('socrates.extractors');

        if (! array_key_exists($countryCode, $extractors)) {
            throw new RuntimeException("Unknown or unsupported country code '$countryCode'");
        }

        return new $extractors[$countryCode];
    }
}
