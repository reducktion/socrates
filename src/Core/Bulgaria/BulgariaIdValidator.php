<?php

namespace Reducktion\Socrates\Core\Bulgaria;

use Reducktion\Socrates\Contracts\IdValidator;
use Reducktion\Socrates\Exceptions\InvalidLengthException;

class BulgariaIdValidator implements IdValidator
{
    public function validate(string $id): bool
    {
        $idLength = strlen($id);

        if ($idLength !== 10) {
            throw new InvalidLengthException('Bulgarian EGN', '10', $idLength);
        }

        $checksum = (int) substr($id, -1);
        $id = substr($id, 0, -1);

        $ucnArray = array_map(
            static function ($digit) {
                return (int) $digit;
            },
            str_split($id)
        );

        $weights = [2, 4, 8, 5, 10, 9, 7, 3, 6];

        $sum = array_sum(
            array_map(
                static function ($digit, $weight) {
                    return $digit * $weight;
                },
                $ucnArray,
                $weights
            )
        );

        return $checksum === ($sum % 11);
    }
}
