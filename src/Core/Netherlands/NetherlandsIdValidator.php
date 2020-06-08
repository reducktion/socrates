<?php

namespace Reducktion\Socrates\Core\Netherlands;

use Reducktion\Socrates\Contracts\IdValidator;
use Reducktion\Socrates\Exceptions\InvalidLengthException;

class NetherlandsIdValidator implements IdValidator
{
    public function validate(string $id): bool
    {
        $idLength = strlen($id);

        if ($idLength !== 9) {
            throw new InvalidLengthException("The Dutch BSN must have 9 digits, got $idLength");
        }

        $lastDigit = (int) substr($id, -1);
        $id = substr($id, 0, -1);

        $bsnArray = array_map(
            static function ($digit) {
                return (int) $digit;
            },
            str_split($id)
        );

        $weights = [9, 8, 7, 6, 5, 4, 3, 2];

        $sum = array_sum(
            array_map(
                static function ($digit, $weight) {
                    return $digit * $weight;
                },
                $bsnArray,
                $weights
            )
        );

        $sum -= $lastDigit;

        return $sum % 11 === 0;
    }
}
