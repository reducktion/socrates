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

        $numbers = '0123456789'; // 0 - 9 (duh)
        $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'; // 0 - 25

        $result = 0;
        for ($position = 0; $position < $idLength; $position++) {
            if ($position % 2) {
                // odd
            } else {
                //even
                $result += is_numeric($id[$position]) ?
                    (int) $id[$position] :
                    strpos($alphabet, $id[$position]);
            }
        }


        $odd = [
            0 => 1,
            1 => 0,
            2 => 5, // +3
            3 => 7, // +4
            4 => 9, // +5
            5 => 13, // +8
            6 => 15, // +9
            7 => 17, // +10
            8 => 19, // +11
            9 => 21, // +12
            'A' => 1,
            'B' => 0,
            'C' => 5
        ];
    }

}