<?php

namespace Reducktion\Socrates\Core\Latvia;

use Reducktion\Socrates\Contracts\IdValidator;
use Reducktion\Socrates\Exceptions\InvalidLengthException;

class LatviaIdValidator implements IdValidator
{

    public function validate(string $id): bool
    {
        $id = $this->sanitize($id);

        if (substr($id, 0, 2) === '32') {
            return true;
        }

        $checksum = (int) substr($id, -1);
        $id = substr($id, 0, -1);

        $pcArray = array_map(
            static function ($digit) {
                return (int) $digit;
            },
            str_split($id)
        );

        $multipliers = [1, 6, 3, 7, 9, 10, 5, 8, 4, 2];

        $sum = array_sum(
            array_map(
                static function ($digit, $multiplier) {
                    return $digit * $multiplier;
                },
                $pcArray,
                $multipliers
            )
        );

        $result = ((1101 - $sum) % 11) % 10;

        return $checksum === $result;
    }

    private function sanitize(string $id): string
    {
        $id = str_replace('-', '', $id);

        $idLength = strlen($id);

        if ($idLength !== 11) {
            throw new InvalidLengthException("The Latvian PC must have 11 digits, got $idLength");
        }

        return $id;
    }
}
