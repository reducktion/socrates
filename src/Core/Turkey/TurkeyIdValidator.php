<?php

namespace Reducktion\Socrates\Core\Turkey;

use Reducktion\Socrates\Contracts\IdValidator;
use Reducktion\Socrates\Exceptions\InvalidLengthException;

class TurkeyIdValidator implements IdValidator
{

    public function validate(string $id): bool
    {
        $idLength = strlen($id);

        if ($idLength !== 11) {
            throw new InvalidLengthException('Turkish TC', '11', $idLength);
        }

        $id = array_map(
            static function ($digit) {
                return (int)$digit;
            },
            str_split($id)
        );

        if ($id[0] === 0) {
            return false;
        }

        $evenDigitSum = 0;
        $oddDigitSum = 0;
        for ($i = 0; $i < 9; $i++) {
            if ($i % 2) {
                $oddDigitSum += $id[$i];
            } else {
                $evenDigitSum += $id[$i];
            }
        }

        $firstControlDigit = (($evenDigitSum * 7) - $oddDigitSum) % 10;

        $secondControlDigit = array_sum(array_slice($id, 0, 10)) % 10;

        return $firstControlDigit === $id[9] && $secondControlDigit === $id[10];
    }
}
