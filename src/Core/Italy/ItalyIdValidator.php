<?php

namespace Reducktion\Socrates\Core\Italy;

use Reducktion\Socrates\Contracts\IdValidator;
use Reducktion\Socrates\Exceptions\InvalidLengthException;

class ItalyIdValidator implements IdValidator
{
    public function validate(string $id): bool
    {
        $idLength = strlen($id);
        if ($idLength !== 16) {
            throw new InvalidLengthException("Italian FC must have 16 digits, got $idLength");
        }

        $id = $this->omocodiaSwap($id);

        $control = substr($id, -1);
        $id = substr($id, 0, -1);

        $checksum = $this->calculateChecksum($id);

        return $control === $checksum;
    }

    private function omocodiaSwap(string $id): string
    {
        $omocodia = [
            0 => 'L',
            1 => 'M',
            2 => 'N',
            3 => 'P',
            4 => 'Q',
            5 => 'R',
            6 => 'S',
            7 => 'T',
            8 => 'U',
            9 => 'V'
        ];
        $numericalCharactersPosition = [6, 7, 9, 10, 12, 13, 14];

        foreach ($numericalCharactersPosition as $characterPosition) {
            if (! is_numeric($id[$characterPosition])) {
                $id[$characterPosition] = array_search($id[$characterPosition], $omocodia, true);
            }
        }
        return $id;
    }

    private function calculateChecksum(string $id): string
    {
        $numbers = '0123456789';
        $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
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
        $idLength = strlen($id);
        $result = 0;
        for ($position = 0; $position < $idLength; $position++) {
            if (($position + 1) % 2 === 0) {
                $result += is_numeric($id[$position]) ?
                    (int) $numbers[(int) $id[$position]] :
                    strpos($alphabet, $id[$position]);
            } else {
                $result += $odd[$id[$position]];
            }
        }
        return $alphabet[$result % 26];
    }
}
