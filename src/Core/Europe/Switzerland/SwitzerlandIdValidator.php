<?php

namespace Reducktion\Socrates\Core\Europe\Switzerland;

use Reducktion\Socrates\Contracts\IdValidator;
use Reducktion\Socrates\Exceptions\InvalidLengthException;

class SwitzerlandIdValidator implements IdValidator
{
    public function validate(string $id): bool
    {
        $id = $this->sanitize($id);

        $id = array_map(
            static function ($digit) {
                return (int)$digit;
            },
            str_split($id)
        );

        $sum = 0;

        for ($i = 0; $i < 12; $i++) {
            if ($i % 2 === 0) {
                $sum += $id[$i];
            } else {
                $sum += $id[$i] * 3;
            }
        }

        $checkDigit = $sum % 10 !== 0
            ? (int) (floor($sum / 10) * 10 + 10) - $sum
            : 0;

        return $id[12] === $checkDigit;
    }

    private function sanitize(string $id): string
    {
        $id = str_replace('.', '', $id);

        $idLength = strlen($id);

        if ($idLength !== 13) {
            throw new InvalidLengthException('Swiss AVH/AVN', '13', $idLength);
        }

        return $id;
    }
}
