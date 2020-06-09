<?php

namespace Reducktion\Socrates\Core\BosniaAndHerzegovina;

use Reducktion\Socrates\Contracts\IdValidator;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Core\Yugoslavia\YugoslaviaIdValidator;

class BosniaAndHerzegovinaIdValidator implements IdValidator
{
    public function validate(string $id): bool
    {
        try {
            $result = YugoslaviaIdValidator::validate($id);
        } catch (InvalidLengthException $e) {
            throw new InvalidLengthException('Bosnian JMBG', $e->getRequiredCharacters(), $e->getGivenCharacters());
        }

        $regionDigits = (int) substr($id, 7, 2);

        if (($regionDigits < 10 || $regionDigits > 19) && $regionDigits !== 1) {
            return false;
        }

        return $result;
    }
}
