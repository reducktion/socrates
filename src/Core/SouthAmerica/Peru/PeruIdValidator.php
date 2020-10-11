<?php

namespace Reducktion\Socrates\Core\SouthAmerica\Peru;

use Reducktion\Socrates\Contracts\IdValidator;
use Reducktion\Socrates\Exceptions\InvalidLengthException;

/**
 * Class PeruIdValidator
 *
 * Algorithm adapted from: [
 * https://www.excelnegocios.com/algoritmo-digito-verificador-dni-en-excel/
 * https://mag.elcomercio.pe/respuestas/cual-es-el-digito-verificador-de-mi-dni-documento-nacional-de-identidad-reniec-peru-nnda-nnlt-noticia/?ref=ecr
 * ]
 *
 * @package Reducktion\Socrates\Core\SouthAmerica\Peru
 */
class PeruIdValidator implements IdValidator
{
    public function validate(string $id): bool
    {
        $id = $this->sanitize($id);

        $sum = 0;
        $factors = [3, 2, 7, 6, 5, 4, 3, 2];
        $numberKeys = [6, 7, 8, 9, 0, 1, 1, 2, 3, 4, 5];
        $charKeys = ['K', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J'];

        $dniDigits = substr($id, 0, -1);

        if (preg_match('/[^0-9]/', $dniDigits)) {
            return false;
        }

        $dniDigits = array_map(
            static function ($digit) {
                return (int)$digit;
            },
            str_split($dniDigits)
        );

        $lastDigit = substr($id, -1);

        $countDniDigits = count($dniDigits);

        for ($i = 0; $i < $countDniDigits; $i++) {
            $sum += $dniDigits[$i] * $factors[$i];
        }

        $key = 11 - ($sum % 11);
        $key = $key === 11 ? 0 : $key;

        return is_numeric($lastDigit)
            ? $numberKeys[$key] === (int)$lastDigit
            : $charKeys[$key] === strtoupper($lastDigit);
    }

    private function sanitize(string $id): string
    {
        $id = preg_replace('/[^\d\w]/', '', $id);

        $idLength = strlen($id);

        if ($idLength !== 9) {
            throw new InvalidLengthException('Peru CUI', '9', $idLength);
        }

        return $id;
    }
}
