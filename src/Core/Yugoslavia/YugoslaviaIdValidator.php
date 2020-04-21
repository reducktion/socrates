<?php

namespace Reducktion\Socrates\Core\Yugoslavia;

use Reducktion\Socrates\Exceptions\InvalidLengthException;

class YugoslaviaIdValidator
{

    public static function validate(string $id): bool
    {
        $id = trim($id);
        $idLength = strlen($id);

        if ($idLength !== 13) {
            throw new InvalidLengthException("got $idLength");
        }

        $checksum = (int) substr($id, -1);
        $id = substr($id, 0, -1);

        $umcnArray = array_map(
            static function ($digit) {
                return (int) $digit;
            },
            str_split($id)
        );

        $sum = (7 * ($umcnArray[0] + $umcnArray[6])) +
            (6 * ($umcnArray[1] + $umcnArray[7])) +
            (5 * ($umcnArray[2] + $umcnArray[8])) +
            (4 * ($umcnArray[3] + $umcnArray[9])) +
            (3 * ($umcnArray[4] + $umcnArray[10])) +
            (2 * ($umcnArray[5] + $umcnArray[11]));

        $result = 11 - ($sum % 11);

        $result = $result === 10 || $result === 11 ? 0 : $result;

        return $checksum === $result;
    }
}