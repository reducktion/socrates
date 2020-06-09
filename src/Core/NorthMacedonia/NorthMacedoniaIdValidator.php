<?php

namespace Reducktion\Socrates\Core\NorthMacedonia;

use Reducktion\Socrates\Contracts\IdValidator;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Core\Yugoslavia\YugoslaviaIdValidator;

class NorthMacedoniaIdValidator implements IdValidator
{
    public function validate(string $id): bool
    {
        try {
            $result = YugoslaviaIdValidator::validate($id);
        } catch (InvalidLengthException $e) {
            throw new InvalidLengthException('Macedonian JMBG', $e->getRequiredCharacters(), $e->getGivenCharacters());
        }

        $regionDigits = (int) substr($id, 7, 2);

        if (($regionDigits < 40 || $regionDigits > 49) && $regionDigits !== 4) {
            return false;
        }

        return $result;
    }
}
