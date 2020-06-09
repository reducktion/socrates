<?php

namespace Reducktion\Socrates\Core\Kosovo;

use Reducktion\Socrates\Contracts\IdValidator;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Core\Yugoslavia\YugoslaviaIdValidator;

class KosovoIdValidator implements IdValidator
{
    public function validate(string $id): bool
    {
        try {
            $result = YugoslaviaIdValidator::validate($id);
        } catch (InvalidLengthException $e) {
            throw new InvalidLengthException('Kosovan JMBG', $e->getRequiredCharacters(), $e->getGivenCharacters());
        }

        $regionDigits = (int) substr($id, 7, 2);

        if (($regionDigits < 90 || $regionDigits > 99) && $regionDigits !== 8) {
            return false;
        }

        return $result;
    }
}
