<?php

namespace Reducktion\Socrates\Core\Finland;

use InvalidArgumentException;
use Reducktion\Socrates\Contracts\IdValidator;
use Reducktion\Socrates\Exceptions\InvalidLengthException;

class FinlandIdValidator implements IdValidator
{
    public function validate(string $id): bool
    {
        $idLength = strlen($id);
        if ($idLength !== 11) {
            throw new InvalidLengthException("Finnish PIC must have 11 digits, got $idLength");
        }

        $control = substr($id, -1);
        $id = substr($id, 0, -1);
        $id = substr_replace($id, '', 6, 1);

        $result = ((int) $id % 31);

        if ($result > 9) {
            $this->getCharacterValue($result);
        }

        // $test = '0123456789ABCDEFHJKLMNPRSTUVWXY';
        // return $test[$result] == $control;

        return $result == $control;

        // DDMMYY => dob
        // C => century ('+' for 1800-1899, '-' for 1900-1999, 'A' for 2000-2099)
        // ZZZ => odd for male, even for female
        // Q => control
    }

    private function getCharacterValue(string $character): string
    {
        switch($character) {
            case 10: 
                return 'A';
            case 11:
                return 'B';
            case 12:
                return 'C';
            case 13:
                return 'D';
            case 14:
                return 'E';
            case 15: 
                return 'F';
            case 16:
                return 'H';
            case 17:
                return 'J';
            case 18:
                return 'K';
            case 19:
                return 'L';
            case 20:
                return 'M';
            case 21:
                return 'N';
            case 22:
                return 'P';
            case 23:
                return 'R';
            case 24:
                return 'S';
            case 25:
                return 'T';
            case 26:
                return 'U';
            case 27:
                return 'V';
            case 28:
                return 'W';
            case 29:
                return 'X';
            case 30:
                return 'Y';
            default:
                throw new InvalidArgumentException("Unrecognised character $character in ID.");
        }
    }
}