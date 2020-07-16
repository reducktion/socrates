<?php

namespace Reducktion\Socrates\Core\Europe\Portugal;

use InvalidArgumentException;
use Reducktion\Socrates\Contracts\IdValidator;
use Reducktion\Socrates\Exceptions\InvalidLengthException;

/**
 * Class PortugalIdValidator
 *
 * Algorithm adapted from: https://www.autenticacao.gov.pt/documents/20126/115760/Valida%C3%A7%C3%A3o+de+N%C3%BAmero+de+Documento+do+Cart%C3%A3o+de+Cidad%C3%A3o.pdf/bdc4eb37-7316-3ff4-164a-f869382b7053.
 *
 * @package Reducktion\Socrates\Core\Portugal
 */
class PortugalIdValidator implements IdValidator
{
    public function validate(string $id): bool
    {
        $id = $this->sanitize($id);

        $sum = 0;
        $toggleDigit = false;

        for ($i = 11; $i >= 0; $i--) {
            $value = $this->getCharacterValue($id[$i]);

            if ($toggleDigit) {
                $value *= 2;

                if ($value > 9) {
                    $value -= 9;
                }
            }

            $sum += $value;
            $toggleDigit = !$toggleDigit;
        }

        return ($sum % 10) === 0;
    }

    private function sanitize(string $id): string
    {
        $id = str_replace(' ', '', $id);

        $id = strtoupper($id);

        $idLength = strlen($id);

        if ($idLength !== 12) {
            throw new InvalidLengthException('Portuguese ID Number', '12', $idLength);
        }

        return $id;
    }

    private function getCharacterValue(string $character): int
    {
        if (is_numeric($character)) {
            return (int) $character;
        }

        switch ($character) {
            case 'A':
                return 10;
            case 'B':
                return 11;
            case 'C':
                return 12;
            case 'D':
                return 13;
            case 'E':
                return 14;
            case 'F':
                return 15;
            case 'G':
                return 16;
            case 'H':
                return 17;
            case 'I':
                return 18;
            case 'J':
                return 19;
            case 'K':
                return 20;
            case 'L':
                return 21;
            case 'M':
                return 22;
            case 'N':
                return 23;
            case 'O':
                return 24;
            case 'P':
                return 25;
            case 'Q':
                return 26;
            case 'R':
                return 27;
            case 'S':
                return 28;
            case 'T':
                return 29;
            case 'U':
                return 30;
            case 'V':
                return 31;
            case 'W':
                return 32;
            case 'X':
                return 33;
            case 'Y':
                return 34;
            case 'Z':
                return 35;
            default:
                throw new InvalidArgumentException("Unrecognised character $character in ID.");
        }
    }
}
