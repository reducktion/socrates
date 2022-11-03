<?php

namespace Reducktion\Socrates\Core;

use Reducktion\Socrates\Constants\Country;
use Reducktion\Socrates\Config\Countries;
use Reducktion\Socrates\Contracts\IdValidator;

abstract class IdValidatorFactory
{
    public static function getValidator(Country $country): IdValidator
    {
        return new Countries::$validators[$country->value]();
    }
}
