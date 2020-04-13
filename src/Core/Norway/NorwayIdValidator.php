<?php

namespace Reducktion\Socrates\Core\Norway;

use Reducktion\Socrates\Contracts\IdValidator;
use Reducktion\Socrates\Exceptions\InvalidLengthException;

class NorwayIdValidator implements IdValidator
{

    public function validate(string $id): bool
    {
        $idLength = strlen($id);

        if ($idLength !== 11) {
            throw new InvalidLengthException("Norwegian fødselsnummer must have 11 digits, got $idLength");
        }



        return true;
    }
}