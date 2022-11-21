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
 * @package Reducktion\Socrates\Core\Europe\Portugal
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

        return match ($character) {
            'A' => 10,
            'B' => 11,
            'C' => 12,
            'D' => 13,
            'E' => 14,
            'F' => 15,
            'G' => 16,
            'H' => 17,
            'I' => 18,
            'J' => 19,
            'K' => 20,
            'L' => 21,
            'M' => 22,
            'N' => 23,
            'O' => 24,
            'P' => 25,
            'Q' => 26,
            'R' => 27,
            'S' => 28,
            'T' => 29,
            'U' => 30,
            'V' => 31,
            'W' => 32,
            'X' => 33,
            'Y' => 34,
            'Z' => 35,
            default => throw new InvalidArgumentException("Unrecognised character $character in ID."),
        };
    }
}
