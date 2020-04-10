<?php

namespace Reducktion\Socrates\Core\Romania;

use Reducktion\Socrates\Contracts\IdValidator;
use Reducktion\Socrates\Exceptions\InvalidLengthException;

class RomaniaIdValidator implements IdValidator
{
    public function validate(string $id): bool
    {
        $idLength = strlen($id);
        if ($idLength !== 13) {
            throw new InvalidLengthException("Romanian CNP must have 13 digits, got $idLength");
        }

        $key = '279146358279';

        $sum = 0;
        for ($i = 0; $i <= 11; $i++) {
            $sum += $id[$i] * $key[$i];
        }

        $sum %= 11;

        if (($sum === 10 && $id[12] !== '1') || ($sum < 10 && $sum != $id[12])) {
            return false;
        }

        return true;
    }
}