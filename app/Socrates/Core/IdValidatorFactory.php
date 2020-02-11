<?php

namespace App\Socrates\Core;

use App\Socrates\Contracts\IdValidator;
use RuntimeException;

abstract class IdValidatorFactory
{
    public static function getValidator(string $countryCode): IdValidator {
        switch ($countryCode) {
            case 'DK':
                return new DenmarkIdValidator();

            default:
                throw new RuntimeException("Unknown or unsupported country code '$countryCode'");
        }
    }
}
