<?php

namespace Reducktion\Socrates\Core\Serbia;

use Reducktion\Socrates\Contracts\IdValidator;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Core\Yugoslavia\YugoslaviaIdValidator;

class SerbiaIdValidator implements IdValidator
{
    public function validate(string $id): bool
    {
        try {
            $result = YugoslaviaIdValidator::validate($id);
        } catch (InvalidLengthException $e) {
            throw new InvalidLengthException('The Serbian JMBG must have 13 digits, ' . $e->getMessage());
        }

        $regionDigits = (int) substr($id, 7, 2);

        if (($regionDigits < 70 || $regionDigits > 89) && ($regionDigits !== 6 && $regionDigits !== 7)) {
            return false;
        }

        return $result;
    }
}
