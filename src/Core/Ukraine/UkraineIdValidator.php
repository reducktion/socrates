<?php

namespace Reducktion\Socrates\Core\Ukraine;

use Reducktion\Socrates\Contracts\IdValidator;
use Reducktion\Socrates\Exceptions\InvalidLengthException;

class UkraineIdValidator implements IdValidator
{

    public function validate(string $id): bool
    {
        $idLength = strlen($id);

        if ($idLength !== 10) {
            throw new InvalidLengthException("Ukrainian TIN must have 10 digits, got $idLength");
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

        $firstNineDigits = array_slice($id, 0, 9);

        $multipliers = [-1, 5, 7, 9, 4, 6, 10, 5, 7];

        $sum = array_sum(
            array_map(
                static function ($digit, $multiplier) {
                    return $digit * $multiplier;
                },
                $firstNineDigits,
                $multipliers
            )
        );

        $controlDigit = ($sum % 11) % 10;

        return $controlDigit === $id[9];
    }
}