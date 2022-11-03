<?php

namespace Reducktion\Socrates\Core\SouthAmerica\Chile;

use Reducktion\Socrates\Contracts\IdValidator;
use Reducktion\Socrates\Exceptions\InvalidLengthException;

/**
 * Class ChileIdValidator
 *
 * Algorithm adapted from: https://es.wikipedia.org/wiki/Anexo:Implementaciones_para_algoritmo_de_rut
 *
 * @package Reducktion\Socrates\Core\SouthAmerica\Chile
 */
class ChileIdValidator implements IdValidator
{
    public function validate(string $id): bool
    {
        $id = $this->sanitize($id);

        $lastDigit = $id[-1];

        $id = substr($id, 0, -1);

        $sum = 1;
        for ($m = 0; $id != 0; $id /= 10) {
            $sum = ($sum + ((int) $id % 10) * (9 - $m++ % 6)) % 11;
        }

        $checksum = chr($sum ? $sum + 47 : 75);

        return $checksum === $lastDigit;
    }

    private function sanitize(string $id): string
    {
        $lastDigitCharIsK = str_ends_with($id, 'K');
        $id = preg_replace('/\D/', '', $id);

        if (is_string($id)) {
            $id .= $lastDigitCharIsK ? 'K' : '';
        }

        $idLength = strlen($id);

        //The RUT number's format is: 7 or 8 digits, one dash, 1 check digit (0-9, K)
        if ($idLength < 8 || $idLength > 9) {
            throw new InvalidLengthException('Chile RUT', '8', $idLength);
        }

        return $id;
    }
}
