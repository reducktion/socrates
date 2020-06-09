<?php

namespace Reducktion\Socrates\Core;

use RuntimeException;
use Reducktion\Socrates\Config\Countries;
use Reducktion\Socrates\Contracts\IdValidator;

abstract class IdValidatorFactory
{
    public static function getValidator(string $countryCode): IdValidator
    {
        if (! isset(Countries::$validators[$countryCode])) {
            throw new RuntimeException("Unknown or unsupported country code '$countryCode'");
        }

        return new Countries::$validators[$countryCode]();
    }
}
