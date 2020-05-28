<?php

namespace Reducktion\Socrates\Core\Albania;

use Reducktion\Socrates\Contracts\IdValidator;
use Reducktion\Socrates\Exceptions\InvalidLengthException;

class AlbaniaIdValidator implements IdValidator
{

    public function validate(string $id): bool
    {
        $idLength = strlen($id);

        if ($idLength !== 10) {
            throw new InvalidLengthException("Albanian NID must have 10 characters, got $idLength");
        }

        if (is_numeric($id[0])) {
            return false;
        }

        $id = strtoupper($id);
        $id = str_split($id);

        $verificationTable = [
            1 => 'A',
            2 => 'B',
            3 => 'C',
            4 => 'D',
            5 => 'E',
            6 => 'F',
            7 => 'G',
            8 => 'H',
            9 => 'I',
            10 => 'J',
            11 => 'K',
            12 => 'L',
            13 => 'M',
            14 => 'N',
            15 => 'O',
            16 => 'P',
            17 => 'Q',
            18 => 'R',
            19 => 'S',
            20 => 'T',
            21 => 'U',
            22 => 'V',
            0 => 'W',
        ];

        $firstLetter = $id[0];
        $checkLetter = $id[9];
        $firstLetterNumber = array_search($firstLetter, $verificationTable);

        $eightMiddleDigits = array_slice($id, 1, 8);

        $sum = array_sum(
            array_map(
                function ($value, $key) {
                    return $value * ($key + 1);
                },
                $eightMiddleDigits,
                array_keys($eightMiddleDigits)
            )
        );

        $checkNumber = ($sum + $firstLetterNumber) % 23;

        return $checkLetter === $verificationTable[$checkNumber];
    }
}
