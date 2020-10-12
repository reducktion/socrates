<?php

namespace Reducktion\Socrates\Core\SouthAmerica\Ecuador;

use Reducktion\Socrates\Contracts\IdValidator;
use Reducktion\Socrates\Exceptions\InvalidLengthException;

/**
 * Class EcuadorIdValidator
 *
 * Algorithm adapted from: [
 * https://www.jybaro.com/blog/cedula-de-identidad-ecuatoriana/
 * https://medium.com/@bryansuarez/c%C3%B3mo-validar-c%C3%A9dula-y-ruc-en-ecuador-b62c5666186f
 * ]
 *
 * @package Reducktion\Socrates\Core\SouthAmerica\Ecuador
 */
class EcuadorIdValidator implements IdValidator
{
    public function validate(string $id): bool
    {
        $id = $this->sanitize($id);

        $provinceCode = (int)substr($id, 0, 2);
        $thirdDigit = (int)substr($id, 2, 1);
        $sequenceNumber = substr($id, 0, 9);
        $lastDigit = (int)$id[-1];

        if ($provinceCode < 0 || $provinceCode > 24) {
            return false;
        }

        if ($thirdDigit < 0 || $thirdDigit > 5) {
            return false;
        }

        return $this->validateCheckDigit($sequenceNumber, $lastDigit);
    }

    private function sanitize(string $id): string
    {
        $id = preg_replace('/[^0-9]/', '', $id);

        $idLength = strlen($id);

        if ($idLength !== 10) {
            throw new InvalidLengthException('Ecuador CI', '10', $idLength);
        }

        return $id;
    }

    protected function validateCheckDigit(string $sequence, int $lastDigit): bool
    {
        $coefficients = [2, 1, 2, 1, 2, 1, 2, 1, 2];

        $sequence = array_map(
            static function ($digit) {
                return (int)$digit;
            },
            str_split($sequence)
        );

        $sum = 0;
        foreach ($sequence as $key => $value) {
            $posValue = $value * $coefficients[$key];

            if ($posValue >= 10) {
                $posValue = (int)array_sum(str_split($posValue));
            }

            $sum += $posValue;
        }

        $remaining = $sum % 10;
        $result = $remaining !== 0 ? 10 - $remaining : 0;

        return $result === $lastDigit;
    }
}
