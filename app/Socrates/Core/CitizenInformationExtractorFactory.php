<?php

namespace App\Socrates\Core;

use App\Socrates\Contracts\CitizenInformationExtractor;
use RuntimeException;

abstract class CitizenInformationExtractorFactory
{
    public static function getExtractor(string $countryCode): CitizenInformationExtractor {

        // When the package is structured without the Laravel app
        // instead of including it like this:
        $extractors = include 'countries-extractors.php';

        // On boot the config files must publish them to the Laravel configs file
        // And use like this
        // $extractors = config('countries-extractors');

        if (! array_key_exists($countryCode, $extractors)) {
            throw new RuntimeException("Unknown or unsupported country code '$countryCode'");
        }

        return $extractors[$countryCode];
    }
}
