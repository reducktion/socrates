<?php

namespace Reducktion\Socrates\Core;

use Reducktion\Socrates\Contracts\IdValidator;
use RuntimeException;

abstract class IdValidatorFactory
{
    public static function getValidator(string $countryCode): IdValidator {

        $validators = config('socrates.validators');

        if (! array_key_exists($countryCode, $validators)) {
            throw new RuntimeException("Unknown or unsupported country code '$countryCode'");
        }

        return new $validators[$countryCode];
    }
}
