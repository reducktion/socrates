<?php

namespace Reducktion\Socrates\Core\Moldova;

use Reducktion\Socrates\Contracts\IdValidator;
use Reducktion\Socrates\Exceptions\InvalidLengthException;

class MoldovaIdValidator implements IdValidator
{

    public function validate(string $id): bool
    {
        $idLength = strlen($id);

        if ($idLength !== 13) {
            throw new InvalidLengthException('Moldovan IDNP', '13', $idLength);
        }

        if (!is_numeric($id)) {
            return false;
        }

        $id = array_map(
            static function ($digit) {
                return (int)$digit;
            },
            str_split($id)
        );

        if ($id[0] !== 0 && $id[0] !== 2) {
            return false;
        }

        $firstTwelveDigits = array_slice($id, 0, 12);

        $multipliers = [7, 3, 1, 7, 3, 1, 7, 3, 1, 7, 3, 1];

        $sum = array_sum(
            array_map(
                static function ($digit, $multiplier) {
                    return $digit * $multiplier;
                },
                $firstTwelveDigits,
                $multipliers
            )
        );

        return ($sum % 10) === $id[12];
    }
}
