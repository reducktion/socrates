<?php

namespace App\Socrates\Core;

use App\Socrates\Contracts\IdValidator;
use RuntimeException;

abstract class IdValidatorFactory
{
    public static function getValidator(string $countryCode): IdValidator {

        // When the package is structured without the Laravel app
        // instead of including it like this:
        $validators = include 'countries-id-validators.php';

        // On boot the config files must publish them to the Laravel configs file
        // And use like this
        // $validators = config('countries-id-validators');

        if (! array_key_exists($countryCode, $validators)) {
            throw new RuntimeException("Unknown or unsupported country code '$countryCode'");
        }

        return $validators[$countryCode];
    }
}
