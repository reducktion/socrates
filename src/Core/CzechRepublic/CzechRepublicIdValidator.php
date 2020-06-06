<?php

namespace Reducktion\Socrates\Core\CzechRepublic;

use Reducktion\Socrates\Contracts\IdValidator;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Core\Czechoslovakia\CzechoslovakiaIdValidator;

class CzechRepublicIdValidator implements IdValidator
{

    public function validate(string $id): bool
    {
        try {
            $result = CzechoslovakiaIdValidator::validate($id);
        } catch (InvalidLengthException $e) {
            throw new InvalidLengthException('Czech RC', $e->getRequiredCharacters(), $e->getGivenCharacters());
        }

        return $result;
    }
}
