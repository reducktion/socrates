<?php

namespace Reducktion\Socrates\Core\Ireland;

use Reducktion\Socrates\Contracts\IdValidator;
use Reducktion\Socrates\Exceptions\InvalidLengthException;

class IrelandIdValidator implements IdValidator
{

    public function validate(string $id): bool
    {
        $idLength = strlen($id);

        if ($idLength !== 8 && $idLength !== 9) {
            throw new InvalidLengthException("The Irish PPS must have 8 or 9 digits, got $idLength");
        }

        $sum = 0;
        if ($idLength === 9) {
            $sum = ((ord($id[8]) - 64) * 9);
            $id = substr($id, 0, -1);
        }

        $checksum = substr($id, -1);
        $id = substr($id, 0, -1);

        $weights = [8, 7, 6, 5, 4, 3, 2];

        $ppsArray = array_map(
            static function ($digit) {
                return (int) $digit;
            },
            str_split($id)
        );

        $sum += array_sum(
            array_map(
                static function ($digit, $weight) {
                    return $digit * $weight;
                },
                $ppsArray,
                $weights
            )
        );

        $mod = $sum % 23;

        if ($mod === 0) {
            $mod = 23;
        }

        return is_numeric($checksum) ? (int) $checksum === $mod : ord($checksum) - 64 === $mod;
    }

}