<?php


namespace Reducktion\Socrates\Core\Italy;


use Reducktion\Socrates\Contracts\IdValidator;
use Reducktion\Socrates\Exceptions\InvalidLengthException;

class ItalyIdValidator implements IdValidator
{

    public function validate(string $id): bool
    {
        // SSS NNN YYMDD ZZZZ X
        // SSS => first three consonants in the family name
        // NNN => the first name consonants
        // YYMDD => dob (M => (A to E, H, L, M, P, R to T), DD => (Add 40 to day of month when female)
        // ZZZZ => are code where person was born
        // X => checksum

        $idLength = strlen($id);

        if ($idLength !== 16) {
            throw new InvalidLengthException("Italian FC must have 16 digits, got $idLength");
        }

        $control = substr($id, -1);
        $id = substr($id, 0, -1);
        $numbers = '0123456789'; // 0 - 9 (duh)
        $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'; // 0 - 25
        $odd = [
            0 => 1,
            1 => 0,
            2 => 5,
            3 => 7,
            4 => 9,
            5 => 13,
            6 => 15,
            7 => 17,
            8 => 19,
            9 => 21,
            'A' => 1,
            'B' => 0,
            'C' => 5,
            'D' => 7,
            'E' => 9,
            'F' => 13,
            'G' => 15,
            'H' => 17,
            'I' => 19,
            'J' => 21,
            'K' => 2,
            'L' => 4,
            'M' => 18,
            'N' => 20,
            'O' => 11,
            'P' => 3,
            'Q' => 6,
            'R' => 8,
            'S' => 12,
            'T' => 14,
            'U' => 16,
            'V' => 10,
            'W' => 22,
            'X' => 25,
            'Y' => 24,
            'Z' => 23
        ];

        $result = 0;
        for ($position = 1; $position < $idLength; $position++) {
            if ($position % 2) {
                // odd
                $result += $odd[$id[$position - 1]];
            } else {
                //even
                $result += is_numeric($id[$position - 1]) ?
                    (int) $numbers[$id[$position - 1]] :
                    strpos($alphabet, $id[$position - 1]);
            }
        }

        $remainder = $result % 26;
        dd($result, $remainder, $alphabet[$remainder], $control);
    }

}