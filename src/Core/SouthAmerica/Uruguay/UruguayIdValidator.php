<?php

namespace Reducktion\Socrates\Core\SouthAmerica\Uruguay;

use Reducktion\Socrates\Contracts\IdValidator;
use Reducktion\Socrates\Exceptions\InvalidLengthException;

/**
 * Class UruguayIdValidator
 *
 * Algorithm adapted from: https://github.com/DiMaNacho/validarci
 *
 * @package Reducktion\Socrates\Core\SouthAmerica\Uruguay
 */
class UruguayIdValidator implements IdValidator
{
    public function validate(string $id): bool
    {
        $id = $this->sanitize($id);
        $lastDigit = (int)$id[-1];
        $id = str_pad($id, 7, '0', STR_PAD_LEFT);

        $a = 0;

        $baseNumber = "2987634";
        for ($i = 0; $i < 7; $i++) {
            $baseDigit = $baseNumber[$i];
            $ciDigit = $id[$i];

            $a += (intval($baseDigit) * intval($ciDigit)) % 10;
        }

        $validationDigit = $a % 10 == 0 ? 0 : 10 - $a % 10;

        return $lastDigit == $validationDigit;
    }

    private function sanitize(string $id): string
    {
        $id = preg_replace('/[^0-9]/', '', $id);

        $idLength = strlen($id);

        if ($idLength != 8) {
            throw new InvalidLengthException('Uruguay CI', '8', $idLength);
        }

        return $id;
    }
}
