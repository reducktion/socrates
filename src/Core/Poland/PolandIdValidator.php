<?php

namespace Reducktion\Socrates\Core\Poland;

use Reducktion\Socrates\Contracts\IdValidator;
use Reducktion\Socrates\Exceptions\InvalidLengthException;

class PolandIdValidator implements IdValidator
{
    public function validate(string $id): bool
    {
        $idLength = strlen($id);

        if ($idLength !== 11) {
            throw new InvalidLengthException('Polish PESEL', '11', $idLength);
        }

        $checksum = (int) substr($id, -1);
        $id = substr($id, 0, -1);

        $pesel = array_map(
            static function ($digit) {
                return (int) $digit;
            },
            str_split($id)
        );

        $multipliers = [9, 7, 3, 1, 9, 7, 3, 1, 9, 7];

        return $checksum === array_sum(
            array_map(
                static function ($digit, $multiplier) {
                    return $digit * $multiplier;
                },
                $pesel,
                $multipliers
            )
        ) % 10;
    }
}
