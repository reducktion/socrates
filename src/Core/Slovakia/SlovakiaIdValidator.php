<?php

namespace Reducktion\Socrates\Core\Slovakia;

use Reducktion\Socrates\Contracts\IdValidator;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Core\Czechoslovakia\CzechoslovakiaIdValidator;

class SlovakiaIdValidator implements IdValidator
{
    public function validate(string $id): bool
    {
        try {
            $result = CzechoslovakiaIdValidator::validate($id);
        } catch (InvalidLengthException $e) {
            throw new InvalidLengthException('The Slovakian RC must have 10 digits, ' . $e->getMessage());
        }

        return $result;
    }
}
